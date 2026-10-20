<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderShipmentItemEntity;
use App\Domains\Order\Requests\OrderShipmentItem\OrderShipmentItemCreateRequest;
use App\Domains\Order\Requests\OrderShipmentItem\OrderShipmentItemDestroyRequest;
use App\Domains\Order\Requests\OrderShipmentItem\OrderShipmentItemQueryRequest;
use App\Domains\Order\Requests\OrderShipmentItem\OrderShipmentItemUpdateRequest;
use App\Domains\Order\Responses\OrderShipmentItem\OrderShipmentItemDestroyResponse;
use App\Domains\Order\Responses\OrderShipmentItem\OrderShipmentItemQueryResponse;
use App\Domains\Order\Responses\OrderShipmentItem\OrderShipmentItemResponse;
use App\Domains\Order\Services\OrderShipmentItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderShipmentItemController extends BaseController
{
    public function __construct(
        private readonly OrderShipmentItemService $orderShipmentItemService,
    ) {}

    #[OA\Post(path: '/orderShipmentItem/search', summary: '查询发货单明细列表接口', security: [['bearerAuth' => []]], tags: ['发货单明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentItemQueryResponse::class),
        ],
    ))]
    public function search(OrderShipmentItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderShipmentItemQueryRequest::getOrderItemId])) {
                $condition[] = [OrderShipmentItemEntity::getOrderItemId, '=', $requestData[OrderShipmentItemQueryRequest::getOrderItemId]];
            }
            if (isset($requestData[OrderShipmentItemQueryRequest::getShipmentId])) {
                $condition[] = [OrderShipmentItemEntity::getShipmentId, '=', $requestData[OrderShipmentItemQueryRequest::getShipmentId]];
            }
            if (isset($requestData[OrderShipmentItemQueryRequest::getId])) {
                $condition[] = [OrderShipmentItemEntity::getId, '=', $requestData[OrderShipmentItemQueryRequest::getId]];
            }

            $result = $this->orderShipmentItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderShipmentItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderShipmentItemQueryResponse::from($result);
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

    #[OA\Post(path: '/orderShipmentItem/store', summary: '新增发货单明细接口', security: [['bearerAuth' => []]], tags: ['发货单明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentItemResponse::class),
        ],
    ))]
    public function store(OrderShipmentItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderShipmentItemEntity::from($requestData);

            if ($this->orderShipmentItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderShipmentItem/show', summary: '获取发货单明细详情接口', security: [['bearerAuth' => []]], tags: ['发货单明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderShipmentItem = $this->orderShipmentItemService->getOneById($id);
            if (empty($orderShipmentItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderShipmentItemResponse::from($orderShipmentItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderShipmentItem/update', summary: '更新发货单明细接口', security: [['bearerAuth' => []]], tags: ['发货单明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentItemResponse::class),
        ],
    ))]
    public function update(OrderShipmentItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderShipmentItem = $this->orderShipmentItemService->getOneById($id);
            if (empty($orderShipmentItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderShipmentItemEntity::from($requestData);

            $this->orderShipmentItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderShipmentItem/destroy', summary: '删除发货单明细接口', security: [['bearerAuth' => []]], tags: ['发货单明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentItemDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderShipmentItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderShipmentItemService->removeByIds($requestData['ids'])) {
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
