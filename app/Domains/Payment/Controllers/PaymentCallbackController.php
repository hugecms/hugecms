<?php

declare(strict_types=1);

namespace App\Domains\Payment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Payment\Entities\PaymentCallbackEntity;
use App\Domains\Payment\Requests\PaymentCallback\PaymentCallbackCreateRequest;
use App\Domains\Payment\Requests\PaymentCallback\PaymentCallbackDestroyRequest;
use App\Domains\Payment\Requests\PaymentCallback\PaymentCallbackQueryRequest;
use App\Domains\Payment\Requests\PaymentCallback\PaymentCallbackUpdateRequest;
use App\Domains\Payment\Responses\PaymentCallback\PaymentCallbackDestroyResponse;
use App\Domains\Payment\Responses\PaymentCallback\PaymentCallbackQueryResponse;
use App\Domains\Payment\Responses\PaymentCallback\PaymentCallbackResponse;
use App\Domains\Payment\Services\PaymentCallbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PaymentCallbackController extends BaseController
{
    public function __construct(
        private readonly PaymentCallbackService $paymentCallbackService,
    ) {}

    #[OA\Post(path: '/paymentCallback/search', summary: '查询支付回调流水列表接口', security: [['bearerAuth' => []]], tags: ['支付回调流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCallbackQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentCallbackQueryResponse::class),
        ],
    ))]
    public function search(PaymentCallbackQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PaymentCallbackQueryRequest::getPaymentId])) {
                $condition[] = [PaymentCallbackEntity::getPaymentId, '=', $requestData[PaymentCallbackQueryRequest::getPaymentId]];
            }
            if (isset($requestData[PaymentCallbackQueryRequest::getTradeNo])) {
                $condition[] = [PaymentCallbackEntity::getTradeNo, '=', $requestData[PaymentCallbackQueryRequest::getTradeNo]];
            }
            if (isset($requestData[PaymentCallbackQueryRequest::getId])) {
                $condition[] = [PaymentCallbackEntity::getId, '=', $requestData[PaymentCallbackQueryRequest::getId]];
            }
            if (isset($requestData[PaymentCallbackQueryRequest::getChannel])) {
                $condition[] = [PaymentCallbackEntity::getChannel, '=', $requestData[PaymentCallbackQueryRequest::getChannel]];
            }
            if (isset($requestData[PaymentCallbackQueryRequest::getNotifyId])) {
                $condition[] = [PaymentCallbackEntity::getNotifyId, '=', $requestData[PaymentCallbackQueryRequest::getNotifyId]];
            }

            $result = $this->paymentCallbackService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PaymentCallbackResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PaymentCallbackQueryResponse::from($result);
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

    #[OA\Post(path: '/paymentCallback/store', summary: '新增支付回调流水接口', security: [['bearerAuth' => []]], tags: ['支付回调流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCallbackCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentCallbackResponse::class),
        ],
    ))]
    public function store(PaymentCallbackCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = PaymentCallbackEntity::from($requestData);

            if ($this->paymentCallbackService->save($input->toEntity())) {
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

    #[OA\Get(path: '/paymentCallback/show', summary: '获取支付回调流水详情接口', security: [['bearerAuth' => []]], tags: ['支付回调流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentCallbackResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $paymentCallback = $this->paymentCallbackService->getOneById($id);
            if (empty($paymentCallback)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PaymentCallbackResponse::from($paymentCallback);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/paymentCallback/update', summary: '更新支付回调流水接口', security: [['bearerAuth' => []]], tags: ['支付回调流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCallbackUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentCallbackResponse::class),
        ],
    ))]
    public function update(PaymentCallbackUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $paymentCallback = $this->paymentCallbackService->getOneById($id);
            if (empty($paymentCallback)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PaymentCallbackEntity::from($requestData);

            $this->paymentCallbackService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/paymentCallback/destroy', summary: '删除支付回调流水接口', security: [['bearerAuth' => []]], tags: ['支付回调流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCallbackDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentCallbackDestroyResponse::class),
        ],
    ))]
    public function destroy(PaymentCallbackDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->paymentCallbackService->removeByIds($requestData['ids'])) {
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
