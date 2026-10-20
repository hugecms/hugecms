<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductStockReminderEntity;
use App\Domains\Product\Requests\ProductStockReminder\ProductStockReminderCreateRequest;
use App\Domains\Product\Requests\ProductStockReminder\ProductStockReminderDestroyRequest;
use App\Domains\Product\Requests\ProductStockReminder\ProductStockReminderQueryRequest;
use App\Domains\Product\Requests\ProductStockReminder\ProductStockReminderUpdateRequest;
use App\Domains\Product\Responses\ProductStockReminder\ProductStockReminderDestroyResponse;
use App\Domains\Product\Responses\ProductStockReminder\ProductStockReminderQueryResponse;
use App\Domains\Product\Responses\ProductStockReminder\ProductStockReminderResponse;
use App\Domains\Product\Services\ProductStockReminderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductStockReminderController extends BaseController
{
    public function __construct(
        private readonly ProductStockReminderService $productStockReminderService,
    ) {}

    #[OA\Post(path: '/productStockReminder/search', summary: '查询到货提醒列表接口', security: [['bearerAuth' => []]], tags: ['到货提醒模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockReminderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockReminderQueryResponse::class),
        ],
    ))]
    public function search(ProductStockReminderQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductStockReminderQueryRequest::getProductId])) {
                $condition[] = [ProductStockReminderEntity::getProductId, '=', $requestData[ProductStockReminderQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductStockReminderQueryRequest::getStatus])) {
                $condition[] = [ProductStockReminderEntity::getStatus, '=', $requestData[ProductStockReminderQueryRequest::getStatus]];
            }
            if (isset($requestData[ProductStockReminderQueryRequest::getId])) {
                $condition[] = [ProductStockReminderEntity::getId, '=', $requestData[ProductStockReminderQueryRequest::getId]];
            }
            if (isset($requestData[ProductStockReminderQueryRequest::getUserId])) {
                $condition[] = [ProductStockReminderEntity::getUserId, '=', $requestData[ProductStockReminderQueryRequest::getUserId]];
            }
            if (isset($requestData[ProductStockReminderQueryRequest::getProductId])) {
                $condition[] = [ProductStockReminderEntity::getProductId, '=', $requestData[ProductStockReminderQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductStockReminderQueryRequest::getSkuId])) {
                $condition[] = [ProductStockReminderEntity::getSkuId, '=', $requestData[ProductStockReminderQueryRequest::getSkuId]];
            }

            $result = $this->productStockReminderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductStockReminderResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductStockReminderQueryResponse::from($result);
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

    #[OA\Post(path: '/productStockReminder/store', summary: '新增到货提醒接口', security: [['bearerAuth' => []]], tags: ['到货提醒模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockReminderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockReminderResponse::class),
        ],
    ))]
    public function store(ProductStockReminderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductStockReminderEntity::from($requestData);

            if ($this->productStockReminderService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productStockReminder/show', summary: '获取到货提醒详情接口', security: [['bearerAuth' => []]], tags: ['到货提醒模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockReminderResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productStockReminder = $this->productStockReminderService->getOneById($id);
            if (empty($productStockReminder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductStockReminderResponse::from($productStockReminder);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productStockReminder/update', summary: '更新到货提醒接口', security: [['bearerAuth' => []]], tags: ['到货提醒模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockReminderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockReminderResponse::class),
        ],
    ))]
    public function update(ProductStockReminderUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productStockReminder = $this->productStockReminderService->getOneById($id);
            if (empty($productStockReminder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductStockReminderEntity::from($requestData);

            $this->productStockReminderService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productStockReminder/destroy', summary: '删除到货提醒接口', security: [['bearerAuth' => []]], tags: ['到货提醒模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockReminderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductStockReminderDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductStockReminderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productStockReminderService->removeByIds($requestData['ids'])) {
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
