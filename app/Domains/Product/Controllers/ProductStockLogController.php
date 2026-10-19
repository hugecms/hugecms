<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductStockLogEntity;
use App\Domains\Product\Services\ProductStockLogService;
use App\Domains\Product\Requests\ProductStockLog\ProductStockLogCreateRequest;
use App\Domains\Product\Requests\ProductStockLog\ProductStockLogDestroyRequest;
use App\Domains\Product\Requests\ProductStockLog\ProductStockLogQueryRequest;
use App\Domains\Product\Requests\ProductStockLog\ProductStockLogUpdateRequest;
use App\Domains\Product\Responses\ProductStockLog\ProductStockLogDestroyResponse;
use App\Domains\Product\Responses\ProductStockLog\ProductStockLogQueryResponse;
use App\Domains\Product\Responses\ProductStockLog\ProductStockLogResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductStockLogController extends BaseController
{
    public function __construct(
        private readonly ProductStockLogService $productStockLogService,
    ) {}

    #[OA\Post(path: '/productStockLog/search', summary: '查询库存流水列表接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockLogQueryResponse::class),
        ],
    ))]
    public function search(ProductStockLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductStockLogQueryRequest::getSkuId])) {
                $condition[] = [ProductStockLogEntity::getSkuId, '=', $requestData[ProductStockLogQueryRequest::getSkuId]];
            }
            if (isset($requestData[ProductStockLogQueryRequest::getRelatedId])) {
                $condition[] = [ProductStockLogEntity::getRelatedId, '=', $requestData[ProductStockLogQueryRequest::getRelatedId]];
            }
            if (isset($requestData[ProductStockLogQueryRequest::getId])) {
                $condition[] = [ProductStockLogEntity::getId, '=', $requestData[ProductStockLogQueryRequest::getId]];
            }
            
            $result = $this->productStockLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductStockLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductStockLogQueryResponse::from($result);
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

    #[OA\Post(path: '/productStockLog/store', summary: '新增库存流水接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockLogResponse::class),
        ],
    ))]
    public function store(ProductStockLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductStockLogEntity::from($requestData);

            if ($this->productStockLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productStockLog/show', summary: '获取库存流水详情接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productStockLog = $this->productStockLogService->getOneById($id);
            if (empty($productStockLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductStockLogResponse::from($productStockLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productStockLog/update', summary: '更新库存流水接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockLogResponse::class),
        ],
    ))]
    public function update(ProductStockLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productStockLog = $this->productStockLogService->getOneById($id);
            if (empty($productStockLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductStockLogEntity::from($requestData);

            $this->productStockLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productStockLog/destroy', summary: '删除库存流水接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockLogDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductStockLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productStockLogService->removeByIds($requestData['ids'])) {
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
