<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderPromotionEntity;
use App\Domains\Order\Requests\OrderPromotion\OrderPromotionCreateRequest;
use App\Domains\Order\Requests\OrderPromotion\OrderPromotionDestroyRequest;
use App\Domains\Order\Requests\OrderPromotion\OrderPromotionQueryRequest;
use App\Domains\Order\Requests\OrderPromotion\OrderPromotionUpdateRequest;
use App\Domains\Order\Responses\OrderPromotion\OrderPromotionDestroyResponse;
use App\Domains\Order\Responses\OrderPromotion\OrderPromotionQueryResponse;
use App\Domains\Order\Responses\OrderPromotion\OrderPromotionResponse;
use App\Domains\Order\Services\OrderPromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderPromotionController extends BaseController
{
    public function __construct(
        private readonly OrderPromotionService $orderPromotionService,
    ) {}

    #[OA\Post(path: '/orderPromotion/search', summary: '查询订单优惠明细列表接口', security: [['bearerAuth' => []]], tags: ['订单优惠明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPromotionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPromotionQueryResponse::class),
        ],
    ))]
    public function search(OrderPromotionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderPromotionQueryRequest::getOrderId])) {
                $condition[] = [OrderPromotionEntity::getOrderId, '=', $requestData[OrderPromotionQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderPromotionQueryRequest::getType])) {
                $condition[] = [OrderPromotionEntity::getType, '=', $requestData[OrderPromotionQueryRequest::getType]];
            }
            if (isset($requestData[OrderPromotionQueryRequest::getRelatedId])) {
                $condition[] = [OrderPromotionEntity::getRelatedId, '=', $requestData[OrderPromotionQueryRequest::getRelatedId]];
            }
            if (isset($requestData[OrderPromotionQueryRequest::getId])) {
                $condition[] = [OrderPromotionEntity::getId, '=', $requestData[OrderPromotionQueryRequest::getId]];
            }

            $result = $this->orderPromotionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderPromotionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderPromotionQueryResponse::from($result);
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

    #[OA\Post(path: '/orderPromotion/store', summary: '新增订单优惠明细接口', security: [['bearerAuth' => []]], tags: ['订单优惠明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPromotionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPromotionResponse::class),
        ],
    ))]
    public function store(OrderPromotionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderPromotionEntity::from($requestData);

            if ($this->orderPromotionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderPromotion/show', summary: '获取订单优惠明细详情接口', security: [['bearerAuth' => []]], tags: ['订单优惠明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPromotionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderPromotion = $this->orderPromotionService->getOneById($id);
            if (empty($orderPromotion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderPromotionResponse::from($orderPromotion);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderPromotion/update', summary: '更新订单优惠明细接口', security: [['bearerAuth' => []]], tags: ['订单优惠明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPromotionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPromotionResponse::class),
        ],
    ))]
    public function update(OrderPromotionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderPromotion = $this->orderPromotionService->getOneById($id);
            if (empty($orderPromotion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderPromotionEntity::from($requestData);

            $this->orderPromotionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderPromotion/destroy', summary: '删除订单优惠明细接口', security: [['bearerAuth' => []]], tags: ['订单优惠明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPromotionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPromotionDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderPromotionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderPromotionService->removeByIds($requestData['ids'])) {
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
