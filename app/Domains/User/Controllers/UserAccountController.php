<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserAccountEntity;
use App\Domains\User\Requests\UserAccount\UserAccountAdjustRequest;
use App\Domains\User\Requests\UserAccount\UserAccountQueryRequest;
use App\Domains\User\Responses\UserAccount\UserAccountQueryResponse;
use App\Domains\User\Responses\UserAccount\UserAccountResponse;
use App\Services\UserAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAccountController extends BaseController
{
    public function __construct(
        private readonly UserAccountService $userAccountService,
    ) {}

    #[OA\Post(path: '/userAccount/search', summary: '查询用户账户列表接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountQueryResponse::class),
        ],
    ))]
    public function search(UserAccountQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAccountQueryRequest::getUserId])) {
                $condition[] = [UserAccountEntity::getUserId, '=', $requestData[UserAccountQueryRequest::getUserId]];
            }

            $result = $this->userAccountService->getRepository()->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $result['data'][$key] = UserAccountResponse::from($item)->toArray();
            }

            $response = UserAccountQueryResponse::from($result);
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

    #[OA\Get(path: '/userAccount/show', summary: '获取用户账户详情接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $account = $this->userAccountService->getRepository()->findById($id);
            if (empty($account)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserAccountResponse::from($account);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/userAccount/adjust', summary: '人工调整余额接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountAdjustRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
        ],
    ))]
    public function adjust(UserAccountAdjustRequest $adjustRequest): JsonResponse
    {
        $requestData = $adjustRequest->post();

        DB::beginTransaction();
        try {
            $this->userAccountService->adjust(
                \intval($requestData[UserAccountAdjustRequest::getUserId]),
                floatval($requestData[UserAccountAdjustRequest::getAmount]),
                (string) ($requestData[UserAccountAdjustRequest::getBizNo] ?? ''),
                (string) $requestData[UserAccountAdjustRequest::getDescription],
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
