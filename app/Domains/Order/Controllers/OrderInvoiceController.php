<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderInvoiceEntity;
use App\Domains\Order\Requests\OrderInvoice\OrderInvoiceCreateRequest;
use App\Domains\Order\Requests\OrderInvoice\OrderInvoiceDestroyRequest;
use App\Domains\Order\Requests\OrderInvoice\OrderInvoiceQueryRequest;
use App\Domains\Order\Requests\OrderInvoice\OrderInvoiceUpdateRequest;
use App\Domains\Order\Responses\OrderInvoice\OrderInvoiceDestroyResponse;
use App\Domains\Order\Responses\OrderInvoice\OrderInvoiceQueryResponse;
use App\Domains\Order\Responses\OrderInvoice\OrderInvoiceResponse;
use App\Domains\Order\Services\OrderInvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderInvoiceController extends BaseController
{
    public function __construct(
        private readonly OrderInvoiceService $orderInvoiceService,
    ) {}

    #[OA\Post(path: '/orderInvoice/search', summary: '查询订单发票表(合规必需:用户开票申请与发票状态跟踪)列表接口', security: [['bearerAuth' => []]], tags: ['订单发票表(合规必需:用户开票申请与发票状态跟踪)模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInvoiceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderInvoiceQueryResponse::class),
        ],
    ))]
    public function search(OrderInvoiceQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderInvoiceQueryRequest::getOrderId])) {
                $condition[] = [OrderInvoiceEntity::getOrderId, '=', $requestData[OrderInvoiceQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderInvoiceQueryRequest::getId])) {
                $condition[] = [OrderInvoiceEntity::getId, '=', $requestData[OrderInvoiceQueryRequest::getId]];
            }

            $result = $this->orderInvoiceService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderInvoiceResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderInvoiceQueryResponse::from($result);
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

    #[OA\Post(path: '/orderInvoice/store', summary: '新增订单发票表(合规必需:用户开票申请与发票状态跟踪)接口', security: [['bearerAuth' => []]], tags: ['订单发票表(合规必需:用户开票申请与发票状态跟踪)模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInvoiceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderInvoiceResponse::class),
        ],
    ))]
    public function store(OrderInvoiceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderInvoiceEntity::from($requestData);

            if ($this->orderInvoiceService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderInvoice/show', summary: '获取订单发票表(合规必需:用户开票申请与发票状态跟踪)详情接口', security: [['bearerAuth' => []]], tags: ['订单发票表(合规必需:用户开票申请与发票状态跟踪)模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderInvoiceResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderInvoice = $this->orderInvoiceService->getOneById($id);
            if (empty($orderInvoice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderInvoiceResponse::from($orderInvoice);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderInvoice/update', summary: '更新订单发票表(合规必需:用户开票申请与发票状态跟踪)接口', security: [['bearerAuth' => []]], tags: ['订单发票表(合规必需:用户开票申请与发票状态跟踪)模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInvoiceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderInvoiceResponse::class),
        ],
    ))]
    public function update(OrderInvoiceUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderInvoice = $this->orderInvoiceService->getOneById($id);
            if (empty($orderInvoice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderInvoiceEntity::from($requestData);

            $this->orderInvoiceService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderInvoice/destroy', summary: '删除订单发票表(合规必需:用户开票申请与发票状态跟踪)接口', security: [['bearerAuth' => []]], tags: ['订单发票表(合规必需:用户开票申请与发票状态跟踪)模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInvoiceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderInvoiceDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderInvoiceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderInvoiceService->removeByIds($requestData['ids'])) {
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
