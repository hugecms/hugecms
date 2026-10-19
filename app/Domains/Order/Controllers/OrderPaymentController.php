<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderPaymentEntity;
use App\Domains\Order\Services\OrderPaymentService;
use App\Domains\Order\Requests\OrderPayment\OrderPaymentCreateRequest;
use App\Domains\Order\Requests\OrderPayment\OrderPaymentDestroyRequest;
use App\Domains\Order\Requests\OrderPayment\OrderPaymentQueryRequest;
use App\Domains\Order\Requests\OrderPayment\OrderPaymentUpdateRequest;
use App\Domains\Order\Responses\OrderPayment\OrderPaymentDestroyResponse;
use App\Domains\Order\Responses\OrderPayment\OrderPaymentQueryResponse;
use App\Domains\Order\Responses\OrderPayment\OrderPaymentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderPaymentController extends BaseController
{
    public function __construct(
        private readonly OrderPaymentService $orderPaymentService,
    ) {}

    #[OA\Post(path: '/orderPayment/search', summary: '查询支付单列表接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPaymentQueryResponse::class),
        ],
    ))]
    public function search(OrderPaymentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderPaymentQueryRequest::getOrderId])) {
                $condition[] = [OrderPaymentEntity::getOrderId, '=', $requestData[OrderPaymentQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderPaymentQueryRequest::getTradeNo])) {
                $condition[] = [OrderPaymentEntity::getTradeNo, '=', $requestData[OrderPaymentQueryRequest::getTradeNo]];
            }
            if (isset($requestData[OrderPaymentQueryRequest::getId])) {
                $condition[] = [OrderPaymentEntity::getId, '=', $requestData[OrderPaymentQueryRequest::getId]];
            }
            if (isset($requestData[OrderPaymentQueryRequest::getPaymentNo])) {
                $condition[] = [OrderPaymentEntity::getPaymentNo, '=', $requestData[OrderPaymentQueryRequest::getPaymentNo]];
            }
            
            $result = $this->orderPaymentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderPaymentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderPaymentQueryResponse::from($result);
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

    #[OA\Post(path: '/orderPayment/store', summary: '新增支付单接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPaymentResponse::class),
        ],
    ))]
    public function store(OrderPaymentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderPaymentEntity::from($requestData);

            if ($this->orderPaymentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderPayment/show', summary: '获取支付单详情接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPaymentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderPayment = $this->orderPaymentService->getOneById($id);
            if (empty($orderPayment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderPaymentResponse::from($orderPayment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderPayment/update', summary: '更新支付单接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPaymentResponse::class),
        ],
    ))]
    public function update(OrderPaymentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderPayment = $this->orderPaymentService->getOneById($id);
            if (empty($orderPayment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderPaymentEntity::from($requestData);

            $this->orderPaymentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderPayment/destroy', summary: '删除支付单接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderPaymentDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderPaymentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderPaymentService->removeByIds($requestData['ids'])) {
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
