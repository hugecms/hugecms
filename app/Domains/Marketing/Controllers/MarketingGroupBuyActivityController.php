<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingGroupBuyActivityEntity;
use App\Domains\Marketing\Requests\MarketingGroupBuyActivity\MarketingGroupBuyActivityCreateRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyActivity\MarketingGroupBuyActivityDestroyRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyActivity\MarketingGroupBuyActivityQueryRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyActivity\MarketingGroupBuyActivityUpdateRequest;
use App\Domains\Marketing\Responses\MarketingGroupBuyActivity\MarketingGroupBuyActivityDestroyResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyActivity\MarketingGroupBuyActivityQueryResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyActivity\MarketingGroupBuyActivityResponse;
use App\Domains\Marketing\Services\MarketingGroupBuyActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingGroupBuyActivityController extends BaseController
{
    public function __construct(
        private readonly MarketingGroupBuyActivityService $marketingGroupBuyActivityService,
    ) {}

    #[OA\Post(path: '/marketingGroupBuyActivity/search', summary: '查询拼团活�列表接口', security: [['bearerAuth' => []]], tags: ['拼团活�模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyActivityQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyActivityQueryResponse::class),
        ],
    ))]
    public function search(MarketingGroupBuyActivityQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingGroupBuyActivityQueryRequest::getSpuId])) {
                $condition[] = [MarketingGroupBuyActivityEntity::getSpuId, '=', $requestData[MarketingGroupBuyActivityQueryRequest::getSpuId]];
            }
            if (isset($requestData[MarketingGroupBuyActivityQueryRequest::getStatus])) {
                $condition[] = [MarketingGroupBuyActivityEntity::getStatus, '=', $requestData[MarketingGroupBuyActivityQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingGroupBuyActivityQueryRequest::getId])) {
                $condition[] = [MarketingGroupBuyActivityEntity::getId, '=', $requestData[MarketingGroupBuyActivityQueryRequest::getId]];
            }

            $result = $this->marketingGroupBuyActivityService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingGroupBuyActivityResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingGroupBuyActivityQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingGroupBuyActivity/store', summary: '新增拼团活�接口', security: [['bearerAuth' => []]], tags: ['拼团活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyActivityCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyActivityResponse::class),
        ],
    ))]
    public function store(MarketingGroupBuyActivityCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingGroupBuyActivityEntity::from($requestData);

            if ($this->marketingGroupBuyActivityService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingGroupBuyActivity/show', summary: '获取拼团活�详情接口', security: [['bearerAuth' => []]], tags: ['拼团活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyActivityResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingGroupBuyActivity = $this->marketingGroupBuyActivityService->getOneById($id);
            if (empty($marketingGroupBuyActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingGroupBuyActivityResponse::from($marketingGroupBuyActivity);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingGroupBuyActivity/update', summary: '更新拼团活�接口', security: [['bearerAuth' => []]], tags: ['拼团活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyActivityResponse::class),
        ],
    ))]
    public function update(MarketingGroupBuyActivityUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingGroupBuyActivity = $this->marketingGroupBuyActivityService->getOneById($id);
            if (empty($marketingGroupBuyActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingGroupBuyActivityEntity::from($requestData);

            $this->marketingGroupBuyActivityService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingGroupBuyActivity/destroy', summary: '删除拼团活�接口', security: [['bearerAuth' => []]], tags: ['拼团活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyActivityDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyActivityDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingGroupBuyActivityDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingGroupBuyActivityService->removeByIds($requestData['ids'])) {
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
