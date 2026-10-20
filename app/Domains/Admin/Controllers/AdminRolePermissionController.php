<?php

declare(strict_types=1);

namespace App\Domains\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Admin\Entities\AdminRolePermissionEntity;
use App\Domains\Admin\Requests\AdminRolePermission\AdminRolePermissionCreateRequest;
use App\Domains\Admin\Requests\AdminRolePermission\AdminRolePermissionDestroyRequest;
use App\Domains\Admin\Requests\AdminRolePermission\AdminRolePermissionQueryRequest;
use App\Domains\Admin\Requests\AdminRolePermission\AdminRolePermissionUpdateRequest;
use App\Domains\Admin\Responses\AdminRolePermission\AdminRolePermissionDestroyResponse;
use App\Domains\Admin\Responses\AdminRolePermission\AdminRolePermissionQueryResponse;
use App\Domains\Admin\Responses\AdminRolePermission\AdminRolePermissionResponse;
use App\Domains\Admin\Services\AdminRolePermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminRolePermissionController extends BaseController
{
    public function __construct(
        private readonly AdminRolePermissionService $adminRolePermissionService,
    ) {}

    #[OA\Post(path: '/adminRolePermission/search', summary: '查询角色权限关联列表接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRolePermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRolePermissionQueryResponse::class),
        ],
    ))]
    public function search(AdminRolePermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminRolePermissionQueryRequest::getPermissionId])) {
                $condition[] = [AdminRolePermissionEntity::getPermissionId, '=', $requestData[AdminRolePermissionQueryRequest::getPermissionId]];
            }
            if (isset($requestData[AdminRolePermissionQueryRequest::getRoleId])) {
                $condition[] = [AdminRolePermissionEntity::getRoleId, '=', $requestData[AdminRolePermissionQueryRequest::getRoleId]];
            }
            if (isset($requestData[AdminRolePermissionQueryRequest::getPermissionId])) {
                $condition[] = [AdminRolePermissionEntity::getPermissionId, '=', $requestData[AdminRolePermissionQueryRequest::getPermissionId]];
            }

            $result = $this->adminRolePermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdminRolePermissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdminRolePermissionQueryResponse::from($result);
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

    #[OA\Post(path: '/adminRolePermission/store', summary: '新增角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRolePermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRolePermissionResponse::class),
        ],
    ))]
    public function store(AdminRolePermissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AdminRolePermissionEntity::from($requestData);

            if ($this->adminRolePermissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminRolePermission/show', summary: '获取角色权限关联详情接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRolePermissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adminRolePermission = $this->adminRolePermissionService->getOneById($id);
            if (empty($adminRolePermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdminRolePermissionResponse::from($adminRolePermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminRolePermission/update', summary: '更新角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRolePermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRolePermissionResponse::class),
        ],
    ))]
    public function update(AdminRolePermissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminRolePermission = $this->adminRolePermissionService->getOneById($id);
            if (empty($adminRolePermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdminRolePermissionEntity::from($requestData);

            $this->adminRolePermissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adminRolePermission/destroy', summary: '删除角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRolePermissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRolePermissionDestroyResponse::class),
        ],
    ))]
    public function destroy(AdminRolePermissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->adminRolePermissionService->removeByIds($requestData['ids'])) {
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
