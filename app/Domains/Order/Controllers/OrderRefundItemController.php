<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderRefundItemEntity;
use App\Domains\Order\Requests\OrderRefundItem\OrderRefundItemCreateRequest;
use App\Domains\Order\Requests\OrderRefundItem\OrderRefundItemDestroyRequest;
use App\Domains\Order\Requests\OrderRefundItem\OrderRefundItemQueryRequest;
use App\Domains\Order\Requests\OrderRefundItem\OrderRefundItemUpdateRequest;
use App\Domains\Order\Responses\OrderRefundItem\OrderRefundItemDestroyResponse;
use App\Domains\Order\Responses\OrderRefundItem\OrderRefundItemQueryResponse;
use App\Domains\Order\Responses\OrderRefundItem\OrderRefundItemResponse;
use App\Domains\Order\Services\OrderRefundItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderRefundItemController extends BaseController
{
    public function __construct(
        private readonly OrderRefundItemService $orderRefundItemService,
    ) {}

    #[OA\Post(path: '/orderRefundItem/search', summary: '查询退货明细列表接口', security: [['bearerAuth' => []]], tags: ['退货明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundItemQueryResponse::class),
        ],
    ))]
    public function search(OrderRefundItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderRefundItemQueryRequest::getOrderItemId])) {
                $condition[] = [OrderRefundItemEntity::getOrderItemId, '=', $requestData[OrderRefundItemQueryRequest::getOrderItemId]];
            }
            if (isset($requestData[OrderRefundItemQueryRequest::getRefundId])) {
                $condition[] = [OrderRefundItemEntity::getRefundId, '=', $requestData[OrderRefundItemQueryRequest::getRefundId]];
            }
            if (isset($requestData[OrderRefundItemQueryRequest::getId])) {
                $condition[] = [OrderRefundItemEntity::getId, '=', $requestData[OrderRefundItemQueryRequest::getId]];
            }

            $result = $this->orderRefundItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderRefundItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderRefundItemQueryResponse::from($result);
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

    #[OA\Post(path: '/orderRefundItem/store', summary: '新增退货明细接口', security: [['bearerAuth' => []]], tags: ['退货明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundItemResponse::class),
        ],
    ))]
    public function store(OrderRefundItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderRefundItemEntity::from($requestData);

            if ($this->orderRefundItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderRefundItem/show', summary: '获取退货明细详情接口', security: [['bearerAuth' => []]], tags: ['退货明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderRefundItem = $this->orderRefundItemService->getOneById($id);
            if (empty($orderRefundItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderRefundItemResponse::from($orderRefundItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderRefundItem/update', summary: '更新退货明细接口', security: [['bearerAuth' => []]], tags: ['退货明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundItemResponse::class),
        ],
    ))]
    public function update(OrderRefundItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderRefundItem = $this->orderRefundItemService->getOneById($id);
            if (empty($orderRefundItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderRefundItemEntity::from($requestData);

            $this->orderRefundItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderRefundItem/destroy', summary: '删除退货明细接口', security: [['bearerAuth' => []]], tags: ['退货明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundItemDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderRefundItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderRefundItemService->removeByIds($requestData['ids'])) {
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
