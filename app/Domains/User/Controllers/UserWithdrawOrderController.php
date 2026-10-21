<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserWithdrawOrderEntity;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderAuditRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderMarkFailRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderMarkSuccessRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderQueryRequest;
use App\Domains\User\Responses\UserWithdrawOrder\UserWithdrawOrderQueryResponse;
use App\Domains\User\Responses\UserWithdrawOrder\UserWithdrawOrderResponse;
use App\Domains\User\Services\UserWithdrawOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserWithdrawOrderController extends BaseController
{
    public function __construct(
        private readonly UserWithdrawOrderService $userWithdrawOrderService,
    ) {}

    #[OA\Post(path: '/userWithdrawOrder/search', summary: '查询提现单列表接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderQueryResponse::class),
        ],
    ))]
    public function search(UserWithdrawOrderQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserWithdrawOrderQueryRequest::getUserId])) {
                $condition[] = [UserWithdrawOrderEntity::getUserId, '=', $requestData[UserWithdrawOrderQueryRequest::getUserId]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getStatus])) {
                $condition[] = [UserWithdrawOrderEntity::getStatus, '=', $requestData[UserWithdrawOrderQueryRequest::getStatus]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getWithdrawNo])) {
                $condition[] = [UserWithdrawOrderEntity::getWithdrawNo, '=', $requestData[UserWithdrawOrderQueryRequest::getWithdrawNo]];
            }

            $result = $this->userWithdrawOrderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $result['data'][$key] = UserWithdrawOrderResponse::from($item)->toArray();
            }

            $response = UserWithdrawOrderQueryResponse::from($result);
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

    #[OA\Get(path: '/userWithdrawOrder/show', summary: '获取提现单详情接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $order = $this->userWithdrawOrderService->getRepository()->findById($id);
            if (empty($order)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserWithdrawOrderResponse::from($order);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/userWithdrawOrder/audit', summary: '提现单审核接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderAuditRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function audit(UserWithdrawOrderAuditRequest $auditRequest): JsonResponse
    {
        $requestData = $auditRequest->post();

        DB::beginTransaction();
        try {
            $this->userWithdrawOrderService->audit(
                \intval($requestData[UserWithdrawOrderAuditRequest::getId]),
                \boolval($requestData[UserWithdrawOrderAuditRequest::getPass]),
                (string) ($requestData[UserWithdrawOrderAuditRequest::getAdminNote] ?? ''),
            );

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

    #[OA\Post(path: '/userWithdrawOrder/markPaying', summary: '标记打款中接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderAuditRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function markPaying(UserWithdrawOrderAuditRequest $payingRequest): JsonResponse
    {
        $requestData = $payingRequest->post();

        DB::beginTransaction();
        try {
            $this->userWithdrawOrderService->markPaying(\intval($requestData[UserWithdrawOrderAuditRequest::getId]));

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

    #[OA\Post(path: '/userWithdrawOrder/markSuccess', summary: '标记打款成功接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderMarkSuccessRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function markSuccess(UserWithdrawOrderMarkSuccessRequest $successRequest): JsonResponse
    {
        $requestData = $successRequest->post();

        DB::beginTransaction();
        try {
            $this->userWithdrawOrderService->markSuccess(
                \intval($requestData[UserWithdrawOrderMarkSuccessRequest::getId]),
                (string) $requestData[UserWithdrawOrderMarkSuccessRequest::getChannelOrderNo],
            );

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

    #[OA\Post(path: '/userWithdrawOrder/markFail', summary: '标记打款失败接口', security: [['bearerAuth' => []]], tags: ['提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderMarkFailRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function markFail(UserWithdrawOrderMarkFailRequest $failRequest): JsonResponse
    {
        $requestData = $failRequest->post();

        DB::beginTransaction();
        try {
            $this->userWithdrawOrderService->markFail(
                \intval($requestData[UserWithdrawOrderMarkFailRequest::getId]),
                (string) $requestData[UserWithdrawOrderMarkFailRequest::getReason],
            );

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
}
