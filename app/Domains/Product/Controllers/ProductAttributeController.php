<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductAttributeEntity;
use App\Domains\Product\Requests\ProductAttribute\ProductAttributeCreateRequest;
use App\Domains\Product\Requests\ProductAttribute\ProductAttributeDestroyRequest;
use App\Domains\Product\Requests\ProductAttribute\ProductAttributeQueryRequest;
use App\Domains\Product\Requests\ProductAttribute\ProductAttributeUpdateRequest;
use App\Domains\Product\Responses\ProductAttribute\ProductAttributeDestroyResponse;
use App\Domains\Product\Responses\ProductAttribute\ProductAttributeQueryResponse;
use App\Domains\Product\Responses\ProductAttribute\ProductAttributeResponse;
use App\Domains\Product\Services\ProductAttributeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductAttributeController extends BaseController
{
    public function __construct(
        private readonly ProductAttributeService $productAttributeService,
    ) {}

    #[OA\Post(path: '/productAttribute/search', summary: '查询商品属性定义列表接口', security: [['bearerAuth' => []]], tags: ['商品属性定义模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeQueryResponse::class),
        ],
    ))]
    public function search(ProductAttributeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductAttributeQueryRequest::getTypeId])) {
                $condition[] = [ProductAttributeEntity::getTypeId, '=', $requestData[ProductAttributeQueryRequest::getTypeId]];
            }
            if (isset($requestData[ProductAttributeQueryRequest::getId])) {
                $condition[] = [ProductAttributeEntity::getId, '=', $requestData[ProductAttributeQueryRequest::getId]];
            }

            $result = $this->productAttributeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductAttributeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductAttributeQueryResponse::from($result);
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

    #[OA\Post(path: '/productAttribute/store', summary: '新增商品属性定义接口', security: [['bearerAuth' => []]], tags: ['商品属性定义模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeResponse::class),
        ],
    ))]
    public function store(ProductAttributeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductAttributeEntity::from($requestData);

            if ($this->productAttributeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productAttribute/show', summary: '获取商品属性定义详情接口', security: [['bearerAuth' => []]], tags: ['商品属性定义模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productAttribute = $this->productAttributeService->getOneById($id);
            if (empty($productAttribute)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductAttributeResponse::from($productAttribute);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productAttribute/update', summary: '更新商品属性定义接口', security: [['bearerAuth' => []]], tags: ['商品属性定义模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeResponse::class),
        ],
    ))]
    public function update(ProductAttributeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productAttribute = $this->productAttributeService->getOneById($id);
            if (empty($productAttribute)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductAttributeEntity::from($requestData);

            $this->productAttributeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productAttribute/destroy', summary: '删除商品属性定义接口', security: [['bearerAuth' => []]], tags: ['商品属性定义模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductAttributeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productAttributeService->removeByIds($requestData['ids'])) {
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
