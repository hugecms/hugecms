<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductBrandEntity;
use App\Domains\Product\Requests\ProductBrand\ProductBrandCreateRequest;
use App\Domains\Product\Requests\ProductBrand\ProductBrandDestroyRequest;
use App\Domains\Product\Requests\ProductBrand\ProductBrandQueryRequest;
use App\Domains\Product\Requests\ProductBrand\ProductBrandUpdateRequest;
use App\Domains\Product\Responses\ProductBrand\ProductBrandDestroyResponse;
use App\Domains\Product\Responses\ProductBrand\ProductBrandQueryResponse;
use App\Domains\Product\Responses\ProductBrand\ProductBrandResponse;
use App\Domains\Product\Services\ProductBrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductBrandController extends BaseController
{
    public function __construct(
        private readonly ProductBrandService $productBrandService,
    ) {}

    #[OA\Post(path: '/productBrand/search', summary: '查询品牌列表接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductBrandQueryResponse::class),
        ],
    ))]
    public function search(ProductBrandQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductBrandQueryRequest::getId])) {
                $condition[] = [ProductBrandEntity::getId, '=', $requestData[ProductBrandQueryRequest::getId]];
            }
            if (isset($requestData[ProductBrandQueryRequest::getName])) {
                $condition[] = [ProductBrandEntity::getName, '=', $requestData[ProductBrandQueryRequest::getName]];
            }

            $result = $this->productBrandService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductBrandResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductBrandQueryResponse::from($result);
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

    #[OA\Post(path: '/productBrand/store', summary: '新增品牌接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductBrandResponse::class),
        ],
    ))]
    public function store(ProductBrandCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductBrandEntity::from($requestData);

            if ($this->productBrandService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productBrand/show', summary: '获取品牌详情接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductBrandResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productBrand = $this->productBrandService->getOneById($id);
            if (empty($productBrand)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductBrandResponse::from($productBrand);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productBrand/update', summary: '更新品牌接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductBrandResponse::class),
        ],
    ))]
    public function update(ProductBrandUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productBrand = $this->productBrandService->getOneById($id);
            if (empty($productBrand)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductBrandEntity::from($requestData);

            $this->productBrandService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productBrand/destroy', summary: '删除品牌接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductBrandDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductBrandDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productBrandService->removeByIds($requestData['ids'])) {
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
