<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductAttributeValueEntity;
use App\Domains\Product\Requests\ProductAttributeValue\ProductAttributeValueCreateRequest;
use App\Domains\Product\Requests\ProductAttributeValue\ProductAttributeValueDestroyRequest;
use App\Domains\Product\Requests\ProductAttributeValue\ProductAttributeValueQueryRequest;
use App\Domains\Product\Requests\ProductAttributeValue\ProductAttributeValueUpdateRequest;
use App\Domains\Product\Responses\ProductAttributeValue\ProductAttributeValueDestroyResponse;
use App\Domains\Product\Responses\ProductAttributeValue\ProductAttributeValueQueryResponse;
use App\Domains\Product\Responses\ProductAttributeValue\ProductAttributeValueResponse;
use App\Domains\Product\Services\ProductAttributeValueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductAttributeValueController extends BaseController
{
    public function __construct(
        private readonly ProductAttributeValueService $productAttributeValueService,
    ) {}

    #[OA\Post(path: '/productAttributeValue/search', summary: '查询商品属性值列表接口', security: [['bearerAuth' => []]], tags: ['商品属性值模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeValueQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeValueQueryResponse::class),
        ],
    ))]
    public function search(ProductAttributeValueQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductAttributeValueQueryRequest::getAttributeId])) {
                $condition[] = [ProductAttributeValueEntity::getAttributeId, '=', $requestData[ProductAttributeValueQueryRequest::getAttributeId]];
            }
            if (isset($requestData[ProductAttributeValueQueryRequest::getId])) {
                $condition[] = [ProductAttributeValueEntity::getId, '=', $requestData[ProductAttributeValueQueryRequest::getId]];
            }
            if (isset($requestData[ProductAttributeValueQueryRequest::getProductId])) {
                $condition[] = [ProductAttributeValueEntity::getProductId, '=', $requestData[ProductAttributeValueQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductAttributeValueQueryRequest::getAttributeId])) {
                $condition[] = [ProductAttributeValueEntity::getAttributeId, '=', $requestData[ProductAttributeValueQueryRequest::getAttributeId]];
            }
            if (isset($requestData[ProductAttributeValueQueryRequest::getValue])) {
                $condition[] = [ProductAttributeValueEntity::getValue, '=', $requestData[ProductAttributeValueQueryRequest::getValue]];
            }

            $result = $this->productAttributeValueService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductAttributeValueResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductAttributeValueQueryResponse::from($result);
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

    #[OA\Post(path: '/productAttributeValue/store', summary: '新增商品属性值接口', security: [['bearerAuth' => []]], tags: ['商品属性值模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeValueCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeValueResponse::class),
        ],
    ))]
    public function store(ProductAttributeValueCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductAttributeValueEntity::from($requestData);

            if ($this->productAttributeValueService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productAttributeValue/show', summary: '获取商品属性值详情接口', security: [['bearerAuth' => []]], tags: ['商品属性值模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeValueResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productAttributeValue = $this->productAttributeValueService->getOneById($id);
            if (empty($productAttributeValue)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductAttributeValueResponse::from($productAttributeValue);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productAttributeValue/update', summary: '更新商品属性值接口', security: [['bearerAuth' => []]], tags: ['商品属性值模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeValueUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeValueResponse::class),
        ],
    ))]
    public function update(ProductAttributeValueUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productAttributeValue = $this->productAttributeValueService->getOneById($id);
            if (empty($productAttributeValue)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductAttributeValueEntity::from($requestData);

            $this->productAttributeValueService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productAttributeValue/destroy', summary: '删除商品属性值接口', security: [['bearerAuth' => []]], tags: ['商品属性值模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeValueDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductAttributeValueDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductAttributeValueDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productAttributeValueService->removeByIds($requestData['ids'])) {
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
