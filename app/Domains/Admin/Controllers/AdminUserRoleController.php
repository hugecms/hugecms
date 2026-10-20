<?php

declare(strict_types=1);

namespace App\Domains\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Admin\Entities\AdminUserRoleEntity;
use App\Domains\Admin\Requests\AdminUserRole\AdminUserRoleCreateRequest;
use App\Domains\Admin\Requests\AdminUserRole\AdminUserRoleDestroyRequest;
use App\Domains\Admin\Requests\AdminUserRole\AdminUserRoleQueryRequest;
use App\Domains\Admin\Requests\AdminUserRole\AdminUserRoleUpdateRequest;
use App\Domains\Admin\Responses\AdminUserRole\AdminUserRoleDestroyResponse;
use App\Domains\Admin\Responses\AdminUserRole\AdminUserRoleQueryResponse;
use App\Domains\Admin\Responses\AdminUserRole\AdminUserRoleResponse;
use App\Domains\Admin\Services\AdminUserRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminUserRoleController extends BaseController
{
    public function __construct(
        private readonly AdminUserRoleService $adminUserRoleService,
    ) {}

    #[OA\Post(path: '/adminUserRole/search', summary: '查询管理员角色关联列表接口', security: [['bearerAuth' => []]], tags: ['管理员角色关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserRoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserRoleQueryResponse::class),
        ],
    ))]
    public function search(AdminUserRoleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminUserRoleQueryRequest::getRoleId])) {
                $condition[] = [AdminUserRoleEntity::getRoleId, '=', $requestData[AdminUserRoleQueryRequest::getRoleId]];
            }
            if (isset($requestData[AdminUserRoleQueryRequest::getAdminUserId])) {
                $condition[] = [AdminUserRoleEntity::getAdminUserId, '=', $requestData[AdminUserRoleQueryRequest::getAdminUserId]];
            }
            if (isset($requestData[AdminUserRoleQueryRequest::getRoleId])) {
                $condition[] = [AdminUserRoleEntity::getRoleId, '=', $requestData[AdminUserRoleQueryRequest::getRoleId]];
            }

            $result = $this->adminUserRoleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdminUserRoleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdminUserRoleQueryResponse::from($result);
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

    #[OA\Post(path: '/adminUserRole/store', summary: '新增管理员角色关联接口', security: [['bearerAuth' => []]], tags: ['管理员角色关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserRoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserRoleResponse::class),
        ],
    ))]
    public function store(AdminUserRoleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AdminUserRoleEntity::from($requestData);

            if ($this->adminUserRoleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminUserRole/show', summary: '获取管理员角色关联详情接口', security: [['bearerAuth' => []]], tags: ['管理员角色关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserRoleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adminUserRole = $this->adminUserRoleService->getOneById($id);
            if (empty($adminUserRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdminUserRoleResponse::from($adminUserRole);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminUserRole/update', summary: '更新管理员角色关联接口', security: [['bearerAuth' => []]], tags: ['管理员角色关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserRoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserRoleResponse::class),
        ],
    ))]
    public function update(AdminUserRoleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminUserRole = $this->adminUserRoleService->getOneById($id);
            if (empty($adminUserRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdminUserRoleEntity::from($requestData);

            $this->adminUserRoleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adminUserRole/destroy', summary: '删除管理员角色关联接口', security: [['bearerAuth' => []]], tags: ['管理员角色关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserRoleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminUserRoleDestroyResponse::class),
        ],
    ))]
    public function destroy(AdminUserRoleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->adminUserRoleService->removeByIds($requestData['ids'])) {
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
