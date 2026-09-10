<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Permission\PermissionCreateRequest;
use App\Api\Admin\Requests\Permission\PermissionDestroyRequest;
use App\Api\Admin\Requests\Permission\PermissionQueryRequest;
use App\Api\Admin\Requests\Permission\PermissionUpdateRequest;
use App\Api\Admin\Responses\Permission\PermissionDestroyResponse;
use App\Api\Admin\Responses\Permission\PermissionQueryResponse;
use App\Api\Admin\Responses\Permission\PermissionResponse;
use App\Entities\PermissionEntity;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PermissionController extends BaseController
{
    public function __construct(
        private readonly PermissionService $permissionService,
    ) {}

    #[OA\Post(path: '/permission/search', summary: '查询权限列表接口', security: [['bearerAuth' => []]], tags: ['权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PermissionQueryResponse::class),
        ],
    ))]
    public function search(PermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[PermissionQueryRequest::getKeyword])) {
                $condition[] = [PermissionEntity::getName, 'like', '%'.$requestData[PermissionQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[PermissionQueryRequest::getCode])) {
                $condition[] = [PermissionEntity::getCode, '=', $requestData[PermissionQueryRequest::getCode]];
            }
            if (isset($requestData[PermissionQueryRequest::getParentId])) {
                $condition[] = [PermissionEntity::getParentId, '=', $requestData[PermissionQueryRequest::getParentId]];
            }
            if (isset($requestData[PermissionQueryRequest::getId])) {
                $condition[] = [PermissionEntity::getId, '=', $requestData[PermissionQueryRequest::getId]];
            }

            $result = $this->permissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PermissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PermissionQueryResponse::from($result);
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

    #[OA\Post(path: '/permission/store', summary: '新增权限接口', security: [['bearerAuth' => []]], tags: ['权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PermissionResponse::class),
        ],
    ))]
    public function store(PermissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = PermissionEntity::from($requestData);

            if ($this->permissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/permission/show', summary: '获取权限详情接口', security: [['bearerAuth' => []]], tags: ['权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PermissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $permission = $this->permissionService->getOneById($id);
            if (empty($permission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PermissionResponse::from($permission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/permission/update', summary: '更新权限接口', security: [['bearerAuth' => []]], tags: ['权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PermissionResponse::class),
        ],
    ))]
    public function update(PermissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $permission = $this->permissionService->getOneById($id);
            if (empty($permission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PermissionEntity::from($requestData);

            $this->permissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/permission/destroy', summary: '删除权限接口', security: [['bearerAuth' => []]], tags: ['权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PermissionDestroyResponse::class),
        ],
    ))]
    public function destroy(PermissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->permissionService->removeByIds($requestData['ids'])) {
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
