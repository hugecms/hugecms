<?php

declare(strict_types=1);

namespace App\Domains\Payment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Payment\Entities\PaymentChannelEntity;
use App\Domains\Payment\Requests\PaymentChannel\PaymentChannelCreateRequest;
use App\Domains\Payment\Requests\PaymentChannel\PaymentChannelDestroyRequest;
use App\Domains\Payment\Requests\PaymentChannel\PaymentChannelQueryRequest;
use App\Domains\Payment\Requests\PaymentChannel\PaymentChannelUpdateRequest;
use App\Domains\Payment\Responses\PaymentChannel\PaymentChannelDestroyResponse;
use App\Domains\Payment\Responses\PaymentChannel\PaymentChannelQueryResponse;
use App\Domains\Payment\Responses\PaymentChannel\PaymentChannelResponse;
use App\Domains\Payment\Services\PaymentChannelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PaymentChannelController extends BaseController
{
    public function __construct(
        private readonly PaymentChannelService $paymentChannelService,
    ) {}

    #[OA\Post(path: '/paymentChannel/search', summary: '查询支付渠道配置列表接口', security: [['bearerAuth' => []]], tags: ['支付渠道配置模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentChannelQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentChannelQueryResponse::class),
        ],
    ))]
    public function search(PaymentChannelQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PaymentChannelQueryRequest::getId])) {
                $condition[] = [PaymentChannelEntity::getId, '=', $requestData[PaymentChannelQueryRequest::getId]];
            }
            if (isset($requestData[PaymentChannelQueryRequest::getCode])) {
                $condition[] = [PaymentChannelEntity::getCode, '=', $requestData[PaymentChannelQueryRequest::getCode]];
            }
            if (isset($requestData[PaymentChannelQueryRequest::getAppid])) {
                $condition[] = [PaymentChannelEntity::getAppid, '=', $requestData[PaymentChannelQueryRequest::getAppid]];
            }

            $result = $this->paymentChannelService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PaymentChannelResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PaymentChannelQueryResponse::from($result);
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

    #[OA\Post(path: '/paymentChannel/store', summary: '新增支付渠道配置接口', security: [['bearerAuth' => []]], tags: ['支付渠道配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentChannelCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentChannelResponse::class),
        ],
    ))]
    public function store(PaymentChannelCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = PaymentChannelEntity::from($requestData);

            if ($this->paymentChannelService->save($input->toEntity())) {
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

    #[OA\Get(path: '/paymentChannel/show', summary: '获取支付渠道配置详情接口', security: [['bearerAuth' => []]], tags: ['支付渠道配置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentChannelResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $paymentChannel = $this->paymentChannelService->getOneById($id);
            if (empty($paymentChannel)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PaymentChannelResponse::from($paymentChannel);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/paymentChannel/update', summary: '更新支付渠道配置接口', security: [['bearerAuth' => []]], tags: ['支付渠道配置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentChannelUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentChannelResponse::class),
        ],
    ))]
    public function update(PaymentChannelUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $paymentChannel = $this->paymentChannelService->getOneById($id);
            if (empty($paymentChannel)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PaymentChannelEntity::from($requestData);

            $this->paymentChannelService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/paymentChannel/destroy', summary: '删除支付渠道配置接口', security: [['bearerAuth' => []]], tags: ['支付渠道配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentChannelDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentChannelDestroyResponse::class),
        ],
    ))]
    public function destroy(PaymentChannelDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->paymentChannelService->removeByIds($requestData['ids'])) {
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
