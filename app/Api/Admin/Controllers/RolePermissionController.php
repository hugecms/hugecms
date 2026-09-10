<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\RolePermissionEntity;
use App\Services\RolePermissionService;
use App\Api\Admin\Requests\RolePermission\RolePermissionCreateRequest;
use App\Api\Admin\Requests\RolePermission\RolePermissionDestroyRequest;
use App\Api\Admin\Requests\RolePermission\RolePermissionQueryRequest;
use App\Api\Admin\Requests\RolePermission\RolePermissionUpdateRequest;
use App\Api\Admin\Responses\RolePermission\RolePermissionDestroyResponse;
use App\Api\Admin\Responses\RolePermission\RolePermissionQueryResponse;
use App\Api\Admin\Responses\RolePermission\RolePermissionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class RolePermissionController extends BaseController
{
    public function __construct(
        private readonly RolePermissionService $rolePermissionService,
    ) {}

    #[OA\Post(path: '/rolePermission/search', summary: '查询角色权限关联列表接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RolePermissionQueryResponse::class),
        ],
    ))]
    public function search(RolePermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[RolePermissionQueryRequest::getId])) {
                $condition[] = [RolePermissionEntity::getId, '=', $requestData[RolePermissionQueryRequest::getId]];
            }
            if (isset($requestData[RolePermissionQueryRequest::getRoleId])) {
                $condition[] = [RolePermissionEntity::getRoleId, '=', $requestData[RolePermissionQueryRequest::getRoleId]];
            }
            if (isset($requestData[RolePermissionQueryRequest::getPermissionId])) {
                $condition[] = [RolePermissionEntity::getPermissionId, '=', $requestData[RolePermissionQueryRequest::getPermissionId]];
            }
            if (isset($requestData[RolePermissionQueryRequest::getPermissionId])) {
                $condition[] = [RolePermissionEntity::getPermissionId, '=', $requestData[RolePermissionQueryRequest::getPermissionId]];
            }
            
            $result = $this->rolePermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = RolePermissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = RolePermissionQueryResponse::from($result);
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

    #[OA\Post(path: '/rolePermission/store', summary: '新增角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RolePermissionResponse::class),
        ],
    ))]
    public function store(RolePermissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = RolePermissionEntity::from($requestData);

            if ($this->rolePermissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/rolePermission/show', summary: '获取角色权限关联详情接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RolePermissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $rolePermission = $this->rolePermissionService->getOneById($id);
            if (empty($rolePermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = RolePermissionResponse::from($rolePermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/rolePermission/update', summary: '更新角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RolePermissionResponse::class),
        ],
    ))]
    public function update(RolePermissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $rolePermission = $this->rolePermissionService->getOneById($id);
            if (empty($rolePermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = RolePermissionEntity::from($requestData);

            $this->rolePermissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/rolePermission/destroy', summary: '删除角色权限关联接口', security: [['bearerAuth' => []]], tags: ['角色权限关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RolePermissionDestroyResponse::class),
        ],
    ))]
    public function destroy(RolePermissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->rolePermissionService->removeByIds($requestData['ids'])) {
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
