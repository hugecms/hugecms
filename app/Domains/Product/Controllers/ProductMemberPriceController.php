<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductMemberPriceEntity;
use App\Domains\Product\Requests\ProductMemberPrice\ProductMemberPriceCreateRequest;
use App\Domains\Product\Requests\ProductMemberPrice\ProductMemberPriceDestroyRequest;
use App\Domains\Product\Requests\ProductMemberPrice\ProductMemberPriceQueryRequest;
use App\Domains\Product\Requests\ProductMemberPrice\ProductMemberPriceUpdateRequest;
use App\Domains\Product\Responses\ProductMemberPrice\ProductMemberPriceDestroyResponse;
use App\Domains\Product\Responses\ProductMemberPrice\ProductMemberPriceQueryResponse;
use App\Domains\Product\Responses\ProductMemberPrice\ProductMemberPriceResponse;
use App\Domains\Product\Services\ProductMemberPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductMemberPriceController extends BaseController
{
    public function __construct(
        private readonly ProductMemberPriceService $productMemberPriceService,
    ) {}

    #[OA\Post(path: '/productMemberPrice/search', summary: '查询商品会员等级价列表接口', security: [['bearerAuth' => []]], tags: ['商品会员等级价模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductMemberPriceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductMemberPriceQueryResponse::class),
        ],
    ))]
    public function search(ProductMemberPriceQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductMemberPriceQueryRequest::getId])) {
                $condition[] = [ProductMemberPriceEntity::getId, '=', $requestData[ProductMemberPriceQueryRequest::getId]];
            }
            if (isset($requestData[ProductMemberPriceQueryRequest::getProductId])) {
                $condition[] = [ProductMemberPriceEntity::getProductId, '=', $requestData[ProductMemberPriceQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductMemberPriceQueryRequest::getRankId])) {
                $condition[] = [ProductMemberPriceEntity::getRankId, '=', $requestData[ProductMemberPriceQueryRequest::getRankId]];
            }

            $result = $this->productMemberPriceService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductMemberPriceResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductMemberPriceQueryResponse::from($result);
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

    #[OA\Post(path: '/productMemberPrice/store', summary: '新增商品会员等级价接口', security: [['bearerAuth' => []]], tags: ['商品会员等级价模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductMemberPriceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductMemberPriceResponse::class),
        ],
    ))]
    public function store(ProductMemberPriceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductMemberPriceEntity::from($requestData);

            if ($this->productMemberPriceService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productMemberPrice/show', summary: '获取商品会员等级价详情接口', security: [['bearerAuth' => []]], tags: ['商品会员等级价模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductMemberPriceResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productMemberPrice = $this->productMemberPriceService->getOneById($id);
            if (empty($productMemberPrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductMemberPriceResponse::from($productMemberPrice);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productMemberPrice/update', summary: '更新商品会员等级价接口', security: [['bearerAuth' => []]], tags: ['商品会员等级价模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductMemberPriceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductMemberPriceResponse::class),
        ],
    ))]
    public function update(ProductMemberPriceUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productMemberPrice = $this->productMemberPriceService->getOneById($id);
            if (empty($productMemberPrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductMemberPriceEntity::from($requestData);

            $this->productMemberPriceService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productMemberPrice/destroy', summary: '删除商品会员等级价接口', security: [['bearerAuth' => []]], tags: ['商品会员等级价模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductMemberPriceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductMemberPriceDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductMemberPriceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productMemberPriceService->removeByIds($requestData['ids'])) {
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
