<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserAccountEntity;
use App\Domains\User\Requests\UserAccount\UserAccountCreateRequest;
use App\Domains\User\Requests\UserAccount\UserAccountDestroyRequest;
use App\Domains\User\Requests\UserAccount\UserAccountQueryRequest;
use App\Domains\User\Requests\UserAccount\UserAccountUpdateRequest;
use App\Domains\User\Responses\UserAccount\UserAccountDestroyResponse;
use App\Domains\User\Responses\UserAccount\UserAccountQueryResponse;
use App\Domains\User\Responses\UserAccount\UserAccountResponse;
use App\Domains\User\Services\UserAccountService;
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
            if (isset($requestData[UserAccountQueryRequest::getId])) {
                $condition[] = [UserAccountEntity::getId, '=', $requestData[UserAccountQueryRequest::getId]];
            }
            if (isset($requestData[UserAccountQueryRequest::getUserId])) {
                $condition[] = [UserAccountEntity::getUserId, '=', $requestData[UserAccountQueryRequest::getUserId]];
            }

            $result = $this->userAccountService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserAccountResponse::from($item);
                $result['data'][$key] = $response->toArray();
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

    #[OA\Post(path: '/userAccount/store', summary: '新增用户账户接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountResponse::class),
        ],
    ))]
    public function store(UserAccountCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserAccountEntity::from($requestData);

            if ($this->userAccountService->save($input->toEntity())) {
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
            $userAccount = $this->userAccountService->getOneById($id);
            if (empty($userAccount)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserAccountResponse::from($userAccount);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAccount/update', summary: '更新用户账户接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountResponse::class),
        ],
    ))]
    public function update(UserAccountUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAccount = $this->userAccountService->getOneById($id);
            if (empty($userAccount)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserAccountEntity::from($requestData);

            $this->userAccountService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userAccount/destroy', summary: '删除用户账户接口', security: [['bearerAuth' => []]], tags: ['用户账户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountDestroyResponse::class),
        ],
    ))]
    public function destroy(UserAccountDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userAccountService->removeByIds($requestData['ids'])) {
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
