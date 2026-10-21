<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingGroupBuyItemEntity;
use App\Domains\Marketing\Requests\MarketingGroupBuyItem\MarketingGroupBuyItemCreateRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyItem\MarketingGroupBuyItemDestroyRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyItem\MarketingGroupBuyItemQueryRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyItem\MarketingGroupBuyItemUpdateRequest;
use App\Domains\Marketing\Responses\MarketingGroupBuyItem\MarketingGroupBuyItemDestroyResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyItem\MarketingGroupBuyItemQueryResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyItem\MarketingGroupBuyItemResponse;
use App\Domains\Marketing\Services\MarketingGroupBuyItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingGroupBuyItemController extends BaseController
{
    public function __construct(
        private readonly MarketingGroupBuyItemService $marketingGroupBuyItemService,
    ) {}

    #[OA\Post(path: '/marketingGroupBuyItem/search', summary: '查询拼团场次商品列表接口', security: [['bearerAuth' => []]], tags: ['拼团场次商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyItemQueryResponse::class),
        ],
    ))]
    public function search(MarketingGroupBuyItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingGroupBuyItemQueryRequest::getSkuId])) {
                $condition[] = [MarketingGroupBuyItemEntity::getSkuId, '=', $requestData[MarketingGroupBuyItemQueryRequest::getSkuId]];
            }
            if (isset($requestData[MarketingGroupBuyItemQueryRequest::getId])) {
                $condition[] = [MarketingGroupBuyItemEntity::getId, '=', $requestData[MarketingGroupBuyItemQueryRequest::getId]];
            }
            if (isset($requestData[MarketingGroupBuyItemQueryRequest::getActivityId])) {
                $condition[] = [MarketingGroupBuyItemEntity::getActivityId, '=', $requestData[MarketingGroupBuyItemQueryRequest::getActivityId]];
            }
            if (isset($requestData[MarketingGroupBuyItemQueryRequest::getSkuId])) {
                $condition[] = [MarketingGroupBuyItemEntity::getSkuId, '=', $requestData[MarketingGroupBuyItemQueryRequest::getSkuId]];
            }

            $result = $this->marketingGroupBuyItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingGroupBuyItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingGroupBuyItemQueryResponse::from($result);
            $response->setFirstPageUrl('');
            $response->setLastPageUrl('');
            $response->setLinks([]);
            $response->setPath('');

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/marketingGroupBuyItem/store', summary: '新增拼团场次商品接口', security: [['bearerAuth' => []]], tags: ['拼团场次商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyItemResponse::class),
        ],
    ))]
    public function store(MarketingGroupBuyItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingGroupBuyItemEntity::from($requestData);

            if ($this->marketingGroupBuyItemService->save($input->toEntity())) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/marketingGroupBuyItem/show', summary: '获取拼团场次商品详情接口', security: [['bearerAuth' => []]], tags: ['拼团场次商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingGroupBuyItem = $this->marketingGroupBuyItemService->getOneById($id);
            if (empty($marketingGroupBuyItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingGroupBuyItemResponse::from($marketingGroupBuyItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingGroupBuyItem/update', summary: '更新拼团场次商品接口', security: [['bearerAuth' => []]], tags: ['拼团场次商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyItemResponse::class),
        ],
    ))]
    public function update(MarketingGroupBuyItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingGroupBuyItem = $this->marketingGroupBuyItemService->getOneById($id);
            if (empty($marketingGroupBuyItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingGroupBuyItemEntity::from($requestData);

            $this->marketingGroupBuyItemService->updateById($input->toEntity(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Post(path: '/marketingGroupBuyItem/destroy', summary: '删除拼团场次商品接口', security: [['bearerAuth' => []]], tags: ['拼团场次商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyItemDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingGroupBuyItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingGroupBuyItemService->removeByIds($requestData['ids'])) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DESTROY_ERROR);
        }
    }
}
