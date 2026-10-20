<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductTypeEntity;
use App\Domains\Product\Requests\ProductType\ProductTypeCreateRequest;
use App\Domains\Product\Requests\ProductType\ProductTypeDestroyRequest;
use App\Domains\Product\Requests\ProductType\ProductTypeQueryRequest;
use App\Domains\Product\Requests\ProductType\ProductTypeUpdateRequest;
use App\Domains\Product\Responses\ProductType\ProductTypeDestroyResponse;
use App\Domains\Product\Responses\ProductType\ProductTypeQueryResponse;
use App\Domains\Product\Responses\ProductType\ProductTypeResponse;
use App\Domains\Product\Services\ProductTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductTypeController extends BaseController
{
    public function __construct(
        private readonly ProductTypeService $productTypeService,
    ) {}

    #[OA\Post(path: '/productType/search', summary: '查询商品类型列表接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductTypeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductTypeQueryResponse::class),
        ],
    ))]
    public function search(ProductTypeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductTypeQueryRequest::getId])) {
                $condition[] = [ProductTypeEntity::getId, '=', $requestData[ProductTypeQueryRequest::getId]];
            }

            $result = $this->productTypeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductTypeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductTypeQueryResponse::from($result);
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

    #[OA\Post(path: '/productType/store', summary: '新增商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductTypeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductTypeResponse::class),
        ],
    ))]
    public function store(ProductTypeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductTypeEntity::from($requestData);

            if ($this->productTypeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productType/show', summary: '获取商品类型详情接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductTypeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productType = $this->productTypeService->getOneById($id);
            if (empty($productType)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductTypeResponse::from($productType);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productType/update', summary: '更新商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductTypeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductTypeResponse::class),
        ],
    ))]
    public function update(ProductTypeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productType = $this->productTypeService->getOneById($id);
            if (empty($productType)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductTypeEntity::from($requestData);

            $this->productTypeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productType/destroy', summary: '删除商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductTypeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductTypeDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductTypeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productTypeService->removeByIds($requestData['ids'])) {
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
