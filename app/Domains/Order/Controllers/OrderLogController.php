<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderLogEntity;
use App\Domains\Order\Requests\OrderLog\OrderLogCreateRequest;
use App\Domains\Order\Requests\OrderLog\OrderLogDestroyRequest;
use App\Domains\Order\Requests\OrderLog\OrderLogQueryRequest;
use App\Domains\Order\Requests\OrderLog\OrderLogUpdateRequest;
use App\Domains\Order\Responses\OrderLog\OrderLogDestroyResponse;
use App\Domains\Order\Responses\OrderLog\OrderLogQueryResponse;
use App\Domains\Order\Responses\OrderLog\OrderLogResponse;
use App\Domains\Order\Services\OrderLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderLogController extends BaseController
{
    public function __construct(
        private readonly OrderLogService $orderLogService,
    ) {}

    #[OA\Post(path: '/orderLog/search', summary: '查询订单操作日志列表接口', security: [['bearerAuth' => []]], tags: ['订单操作日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderLogQueryResponse::class),
        ],
    ))]
    public function search(OrderLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderLogQueryRequest::getOrderId])) {
                $condition[] = [OrderLogEntity::getOrderId, '=', $requestData[OrderLogQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderLogQueryRequest::getId])) {
                $condition[] = [OrderLogEntity::getId, '=', $requestData[OrderLogQueryRequest::getId]];
            }

            $result = $this->orderLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderLogQueryResponse::from($result);
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

    #[OA\Post(path: '/orderLog/store', summary: '新增订单操作日志接口', security: [['bearerAuth' => []]], tags: ['订单操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderLogResponse::class),
        ],
    ))]
    public function store(OrderLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderLogEntity::from($requestData);

            if ($this->orderLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderLog/show', summary: '获取订单操作日志详情接口', security: [['bearerAuth' => []]], tags: ['订单操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderLog = $this->orderLogService->getOneById($id);
            if (empty($orderLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderLogResponse::from($orderLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderLog/update', summary: '更新订单操作日志接口', security: [['bearerAuth' => []]], tags: ['订单操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderLogResponse::class),
        ],
    ))]
    public function update(OrderLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderLog = $this->orderLogService->getOneById($id);
            if (empty($orderLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderLogEntity::from($requestData);

            $this->orderLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderLog/destroy', summary: '删除订单操作日志接口', security: [['bearerAuth' => []]], tags: ['订单操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderLogDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderLogService->removeByIds($requestData['ids'])) {
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
