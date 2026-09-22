<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Role\RoleCreateRequest;
use App\Api\Admin\Requests\Role\RoleDestroyRequest;
use App\Api\Admin\Requests\Role\RoleQueryRequest;
use App\Api\Admin\Requests\Role\RoleUpdateRequest;
use App\Api\Admin\Responses\Role\RoleDestroyResponse;
use App\Api\Admin\Responses\Role\RoleQueryResponse;
use App\Api\Admin\Responses\Role\RoleResponse;
use App\Entities\RoleEntity;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class RoleController extends BaseController
{
    public function __construct(
        private readonly RoleService $roleService,
    ) {}

    #[OA\Post(path: '/role/search', summary: '查询角色列表接口', security: [['bearerAuth' => []]], tags: ['角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RoleQueryResponse::class),
        ],
    ))]
    public function search(RoleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[RoleQueryRequest::getKeyword])) {
                $condition[] = [RoleEntity::getName, 'like', '%'.$requestData[RoleQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[RoleQueryRequest::getId])) {
                $condition[] = [RoleEntity::getId, '=', $requestData[RoleQueryRequest::getId]];
            }
            if (isset($requestData[RoleQueryRequest::getAlias])) {
                $condition[] = [RoleEntity::getAlias, '=', $requestData[RoleQueryRequest::getAlias]];
            }

            $result = $this->roleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = RoleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = RoleQueryResponse::from($result);
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

    #[OA\Post(path: '/role/store', summary: '新增角色接口', security: [['bearerAuth' => []]], tags: ['角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RoleResponse::class),
        ],
    ))]
    public function store(RoleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = RoleEntity::from($requestData);

            if ($this->roleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/role/show', summary: '获取角色详情接口', security: [['bearerAuth' => []]], tags: ['角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RoleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $role = $this->roleService->getOneById($id);
            if (empty($role)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = RoleResponse::from($role);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/role/update', summary: '更新角色接口', security: [['bearerAuth' => []]], tags: ['角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RoleResponse::class),
        ],
    ))]
    public function update(RoleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $role = $this->roleService->getOneById($id);
            if (empty($role)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = RoleEntity::from($requestData);

            $this->roleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/role/destroy', summary: '删除角色接口', security: [['bearerAuth' => []]], tags: ['角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RoleDestroyResponse::class),
        ],
    ))]
    public function destroy(RoleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->roleService->removeByIds($requestData['ids'])) {
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
