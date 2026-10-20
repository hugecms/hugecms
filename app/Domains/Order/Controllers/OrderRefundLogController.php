<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderRefundLogEntity;
use App\Domains\Order\Requests\OrderRefundLog\OrderRefundLogCreateRequest;
use App\Domains\Order\Requests\OrderRefundLog\OrderRefundLogDestroyRequest;
use App\Domains\Order\Requests\OrderRefundLog\OrderRefundLogQueryRequest;
use App\Domains\Order\Requests\OrderRefundLog\OrderRefundLogUpdateRequest;
use App\Domains\Order\Responses\OrderRefundLog\OrderRefundLogDestroyResponse;
use App\Domains\Order\Responses\OrderRefundLog\OrderRefundLogQueryResponse;
use App\Domains\Order\Responses\OrderRefundLog\OrderRefundLogResponse;
use App\Domains\Order\Services\OrderRefundLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderRefundLogController extends BaseController
{
    public function __construct(
        private readonly OrderRefundLogService $orderRefundLogService,
    ) {}

    #[OA\Post(path: '/orderRefundLog/search', summary: '查询售后协商日志列表接口', security: [['bearerAuth' => []]], tags: ['售后协商日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundLogQueryResponse::class),
        ],
    ))]
    public function search(OrderRefundLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderRefundLogQueryRequest::getRefundId])) {
                $condition[] = [OrderRefundLogEntity::getRefundId, '=', $requestData[OrderRefundLogQueryRequest::getRefundId]];
            }
            if (isset($requestData[OrderRefundLogQueryRequest::getId])) {
                $condition[] = [OrderRefundLogEntity::getId, '=', $requestData[OrderRefundLogQueryRequest::getId]];
            }

            $result = $this->orderRefundLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderRefundLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderRefundLogQueryResponse::from($result);
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

    #[OA\Post(path: '/orderRefundLog/store', summary: '新增售后协商日志接口', security: [['bearerAuth' => []]], tags: ['售后协商日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundLogResponse::class),
        ],
    ))]
    public function store(OrderRefundLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderRefundLogEntity::from($requestData);

            if ($this->orderRefundLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderRefundLog/show', summary: '获取售后协商日志详情接口', security: [['bearerAuth' => []]], tags: ['售后协商日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderRefundLog = $this->orderRefundLogService->getOneById($id);
            if (empty($orderRefundLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderRefundLogResponse::from($orderRefundLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderRefundLog/update', summary: '更新售后协商日志接口', security: [['bearerAuth' => []]], tags: ['售后协商日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundLogResponse::class),
        ],
    ))]
    public function update(OrderRefundLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderRefundLog = $this->orderRefundLogService->getOneById($id);
            if (empty($orderRefundLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderRefundLogEntity::from($requestData);

            $this->orderRefundLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderRefundLog/destroy', summary: '删除售后协商日志接口', security: [['bearerAuth' => []]], tags: ['售后协商日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundLogDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderRefundLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderRefundLogService->removeByIds($requestData['ids'])) {
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
