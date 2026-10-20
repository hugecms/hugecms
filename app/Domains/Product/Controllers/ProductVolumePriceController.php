<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductVolumePriceEntity;
use App\Domains\Product\Requests\ProductVolumePrice\ProductVolumePriceCreateRequest;
use App\Domains\Product\Requests\ProductVolumePrice\ProductVolumePriceDestroyRequest;
use App\Domains\Product\Requests\ProductVolumePrice\ProductVolumePriceQueryRequest;
use App\Domains\Product\Requests\ProductVolumePrice\ProductVolumePriceUpdateRequest;
use App\Domains\Product\Responses\ProductVolumePrice\ProductVolumePriceDestroyResponse;
use App\Domains\Product\Responses\ProductVolumePrice\ProductVolumePriceQueryResponse;
use App\Domains\Product\Responses\ProductVolumePrice\ProductVolumePriceResponse;
use App\Domains\Product\Services\ProductVolumePriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductVolumePriceController extends BaseController
{
    public function __construct(
        private readonly ProductVolumePriceService $productVolumePriceService,
    ) {}

    #[OA\Post(path: '/productVolumePrice/search', summary: '查询商品阶梯价列表接口', security: [['bearerAuth' => []]], tags: ['商品阶梯价模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductVolumePriceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductVolumePriceQueryResponse::class),
        ],
    ))]
    public function search(ProductVolumePriceQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductVolumePriceQueryRequest::getId])) {
                $condition[] = [ProductVolumePriceEntity::getId, '=', $requestData[ProductVolumePriceQueryRequest::getId]];
            }
            if (isset($requestData[ProductVolumePriceQueryRequest::getProductId])) {
                $condition[] = [ProductVolumePriceEntity::getProductId, '=', $requestData[ProductVolumePriceQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductVolumePriceQueryRequest::getSkuId])) {
                $condition[] = [ProductVolumePriceEntity::getSkuId, '=', $requestData[ProductVolumePriceQueryRequest::getSkuId]];
            }
            if (isset($requestData[ProductVolumePriceQueryRequest::getMinQuantity])) {
                $condition[] = [ProductVolumePriceEntity::getMinQuantity, '=', $requestData[ProductVolumePriceQueryRequest::getMinQuantity]];
            }

            $result = $this->productVolumePriceService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductVolumePriceResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductVolumePriceQueryResponse::from($result);
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

    #[OA\Post(path: '/productVolumePrice/store', summary: '新增商品阶梯价接口', security: [['bearerAuth' => []]], tags: ['商品阶梯价模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductVolumePriceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductVolumePriceResponse::class),
        ],
    ))]
    public function store(ProductVolumePriceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductVolumePriceEntity::from($requestData);

            if ($this->productVolumePriceService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productVolumePrice/show', summary: '获取商品阶梯价详情接口', security: [['bearerAuth' => []]], tags: ['商品阶梯价模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductVolumePriceResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productVolumePrice = $this->productVolumePriceService->getOneById($id);
            if (empty($productVolumePrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductVolumePriceResponse::from($productVolumePrice);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productVolumePrice/update', summary: '更新商品阶梯价接口', security: [['bearerAuth' => []]], tags: ['商品阶梯价模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductVolumePriceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductVolumePriceResponse::class),
        ],
    ))]
    public function update(ProductVolumePriceUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productVolumePrice = $this->productVolumePriceService->getOneById($id);
            if (empty($productVolumePrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductVolumePriceEntity::from($requestData);

            $this->productVolumePriceService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productVolumePrice/destroy', summary: '删除商品阶梯价接口', security: [['bearerAuth' => []]], tags: ['商品阶梯价模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductVolumePriceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductVolumePriceDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductVolumePriceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productVolumePriceService->removeByIds($requestData['ids'])) {
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
