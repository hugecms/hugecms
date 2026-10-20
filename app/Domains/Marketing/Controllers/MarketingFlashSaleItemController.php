<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingFlashSaleItemEntity;
use App\Domains\Marketing\Requests\MarketingFlashSaleItem\MarketingFlashSaleItemCreateRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleItem\MarketingFlashSaleItemDestroyRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleItem\MarketingFlashSaleItemQueryRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleItem\MarketingFlashSaleItemUpdateRequest;
use App\Domains\Marketing\Responses\MarketingFlashSaleItem\MarketingFlashSaleItemDestroyResponse;
use App\Domains\Marketing\Responses\MarketingFlashSaleItem\MarketingFlashSaleItemQueryResponse;
use App\Domains\Marketing\Responses\MarketingFlashSaleItem\MarketingFlashSaleItemResponse;
use App\Domains\Marketing\Services\MarketingFlashSaleItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingFlashSaleItemController extends BaseController
{
    public function __construct(
        private readonly MarketingFlashSaleItemService $marketingFlashSaleItemService,
    ) {}

    #[OA\Post(path: '/marketingFlashSaleItem/search', summary: '查询秒杀商品列表接口', security: [['bearerAuth' => []]], tags: ['秒杀商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleItemQueryResponse::class),
        ],
    ))]
    public function search(MarketingFlashSaleItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getProductId])) {
                $condition[] = [MarketingFlashSaleItemEntity::getProductId, '=', $requestData[MarketingFlashSaleItemQueryRequest::getProductId]];
            }
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getStatus])) {
                $condition[] = [MarketingFlashSaleItemEntity::getStatus, '=', $requestData[MarketingFlashSaleItemQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getId])) {
                $condition[] = [MarketingFlashSaleItemEntity::getId, '=', $requestData[MarketingFlashSaleItemQueryRequest::getId]];
            }
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getSaleId])) {
                $condition[] = [MarketingFlashSaleItemEntity::getSaleId, '=', $requestData[MarketingFlashSaleItemQueryRequest::getSaleId]];
            }
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getProductId])) {
                $condition[] = [MarketingFlashSaleItemEntity::getProductId, '=', $requestData[MarketingFlashSaleItemQueryRequest::getProductId]];
            }
            if (isset($requestData[MarketingFlashSaleItemQueryRequest::getSkuId])) {
                $condition[] = [MarketingFlashSaleItemEntity::getSkuId, '=', $requestData[MarketingFlashSaleItemQueryRequest::getSkuId]];
            }

            $result = $this->marketingFlashSaleItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingFlashSaleItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingFlashSaleItemQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingFlashSaleItem/store', summary: '新增秒杀商品接口', security: [['bearerAuth' => []]], tags: ['秒杀商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleItemResponse::class),
        ],
    ))]
    public function store(MarketingFlashSaleItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingFlashSaleItemEntity::from($requestData);

            if ($this->marketingFlashSaleItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingFlashSaleItem/show', summary: '获取秒杀商品详情接口', security: [['bearerAuth' => []]], tags: ['秒杀商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingFlashSaleItem = $this->marketingFlashSaleItemService->getOneById($id);
            if (empty($marketingFlashSaleItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingFlashSaleItemResponse::from($marketingFlashSaleItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingFlashSaleItem/update', summary: '更新秒杀商品接口', security: [['bearerAuth' => []]], tags: ['秒杀商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleItemResponse::class),
        ],
    ))]
    public function update(MarketingFlashSaleItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingFlashSaleItem = $this->marketingFlashSaleItemService->getOneById($id);
            if (empty($marketingFlashSaleItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingFlashSaleItemEntity::from($requestData);

            $this->marketingFlashSaleItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingFlashSaleItem/destroy', summary: '删除秒杀商品接口', security: [['bearerAuth' => []]], tags: ['秒杀商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleItemDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingFlashSaleItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingFlashSaleItemService->removeByIds($requestData['ids'])) {
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
