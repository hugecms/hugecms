<?php

declare(strict_types=1);

namespace App\Domains\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Admin\Entities\AdminUserEntity;
use App\Domains\Admin\Requests\AdminUser\AdminUserCreateRequest;
use App\Domains\Admin\Requests\AdminUser\AdminUserDestroyRequest;
use App\Domains\Admin\Requests\AdminUser\AdminUserQueryRequest;
use App\Domains\Admin\Requests\AdminUser\AdminUserUpdateRequest;
use App\Domains\Admin\Responses\AdminUser\AdminUserDestroyResponse;
use App\Domains\Admin\Responses\AdminUser\AdminUserQueryResponse;
use App\Domains\Admin\Responses\AdminUser\AdminUserResponse;
use App\Domains\Admin\Services\AdminUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminUserController extends BaseController
{
    public function __construct(
        private readonly AdminUserService $adminUserService,
    ) {}

    #[OA\Post(path: '/adminUser/search', summary: '查询后台管理员账户列表接口', security: [['bearerAuth' => []]], tags: ['后台管理员账户模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserQueryResponse::class),
        ],
    ))]
    public function search(AdminUserQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminUserQueryRequest::getId])) {
                $condition[] = [AdminUserEntity::getId, '=', $requestData[AdminUserQueryRequest::getId]];
            }
            if (isset($requestData[AdminUserQueryRequest::getEmail])) {
                $condition[] = [AdminUserEntity::getEmail, '=', $requestData[AdminUserQueryRequest::getEmail]];
            }
            if (isset($requestData[AdminUserQueryRequest::getPhone])) {
                $condition[] = [AdminUserEntity::getPhone, '=', $requestData[AdminUserQueryRequest::getPhone]];
            }
            if (isset($requestData[AdminUserQueryRequest::getUsername])) {
                $condition[] = [AdminUserEntity::getUsername, '=', $requestData[AdminUserQueryRequest::getUsername]];
            }

            $result = $this->adminUserService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdminUserResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdminUserQueryResponse::from($result);
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

    #[OA\Post(path: '/adminUser/store', summary: '新增后台管理员账户接口', security: [['bearerAuth' => []]], tags: ['后台管理员账户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserResponse::class),
        ],
    ))]
    public function store(AdminUserCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AdminUserEntity::from($requestData);

            if ($this->adminUserService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminUser/show', summary: '获取后台管理员账户详情接口', security: [['bearerAuth' => []]], tags: ['后台管理员账户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adminUser = $this->adminUserService->getOneById($id);
            if (empty($adminUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdminUserResponse::from($adminUser);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminUser/update', summary: '更新后台管理员账户接口', security: [['bearerAuth' => []]], tags: ['后台管理员账户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserResponse::class),
        ],
    ))]
    public function update(AdminUserUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminUser = $this->adminUserService->getOneById($id);
            if (empty($adminUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdminUserEntity::from($requestData);

            $this->adminUserService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adminUser/destroy', summary: '删除后台管理员账户接口', security: [['bearerAuth' => []]], tags: ['后台管理员账户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserDestroyResponse::class),
        ],
    ))]
    public function destroy(AdminUserDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->adminUserService->removeByIds($requestData['ids'])) {
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
