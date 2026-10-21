<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingBargainItemEntity;
use App\Domains\Marketing\Requests\MarketingBargainItem\MarketingBargainItemCreateRequest;
use App\Domains\Marketing\Requests\MarketingBargainItem\MarketingBargainItemDestroyRequest;
use App\Domains\Marketing\Requests\MarketingBargainItem\MarketingBargainItemQueryRequest;
use App\Domains\Marketing\Requests\MarketingBargainItem\MarketingBargainItemUpdateRequest;
use App\Domains\Marketing\Responses\MarketingBargainItem\MarketingBargainItemDestroyResponse;
use App\Domains\Marketing\Responses\MarketingBargainItem\MarketingBargainItemQueryResponse;
use App\Domains\Marketing\Responses\MarketingBargainItem\MarketingBargainItemResponse;
use App\Domains\Marketing\Services\MarketingBargainItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingBargainItemController extends BaseController
{
    public function __construct(
        private readonly MarketingBargainItemService $marketingBargainItemService,
    ) {}

    #[OA\Post(path: '/marketingBargainItem/search', summary: '查询砍价场次商品列表接口', security: [['bearerAuth' => []]], tags: ['砍价场次商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainItemQueryResponse::class),
        ],
    ))]
    public function search(MarketingBargainItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingBargainItemQueryRequest::getSkuId])) {
                $condition[] = [MarketingBargainItemEntity::getSkuId, '=', $requestData[MarketingBargainItemQueryRequest::getSkuId]];
            }
            if (isset($requestData[MarketingBargainItemQueryRequest::getId])) {
                $condition[] = [MarketingBargainItemEntity::getId, '=', $requestData[MarketingBargainItemQueryRequest::getId]];
            }
            if (isset($requestData[MarketingBargainItemQueryRequest::getActivityId])) {
                $condition[] = [MarketingBargainItemEntity::getActivityId, '=', $requestData[MarketingBargainItemQueryRequest::getActivityId]];
            }
            if (isset($requestData[MarketingBargainItemQueryRequest::getSkuId])) {
                $condition[] = [MarketingBargainItemEntity::getSkuId, '=', $requestData[MarketingBargainItemQueryRequest::getSkuId]];
            }

            $result = $this->marketingBargainItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingBargainItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingBargainItemQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingBargainItem/store', summary: '新增砍价场次商品接口', security: [['bearerAuth' => []]], tags: ['砍价场次商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainItemResponse::class),
        ],
    ))]
    public function store(MarketingBargainItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingBargainItemEntity::from($requestData);

            if ($this->marketingBargainItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingBargainItem/show', summary: '获取砍价场次商品详情接口', security: [['bearerAuth' => []]], tags: ['砍价场次商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingBargainItem = $this->marketingBargainItemService->getOneById($id);
            if (empty($marketingBargainItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingBargainItemResponse::from($marketingBargainItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingBargainItem/update', summary: '更新砍价场次商品接口', security: [['bearerAuth' => []]], tags: ['砍价场次商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainItemResponse::class),
        ],
    ))]
    public function update(MarketingBargainItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingBargainItem = $this->marketingBargainItemService->getOneById($id);
            if (empty($marketingBargainItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingBargainItemEntity::from($requestData);

            $this->marketingBargainItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingBargainItem/destroy', summary: '删除砍价场次商品接口', security: [['bearerAuth' => []]], tags: ['砍价场次商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainItemDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingBargainItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingBargainItemService->removeByIds($requestData['ids'])) {
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
