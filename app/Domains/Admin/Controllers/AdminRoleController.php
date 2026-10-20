<?php

declare(strict_types=1);

namespace App\Domains\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Admin\Entities\AdminRoleEntity;
use App\Domains\Admin\Requests\AdminRole\AdminRoleCreateRequest;
use App\Domains\Admin\Requests\AdminRole\AdminRoleDestroyRequest;
use App\Domains\Admin\Requests\AdminRole\AdminRoleQueryRequest;
use App\Domains\Admin\Requests\AdminRole\AdminRoleUpdateRequest;
use App\Domains\Admin\Responses\AdminRole\AdminRoleDestroyResponse;
use App\Domains\Admin\Responses\AdminRole\AdminRoleQueryResponse;
use App\Domains\Admin\Responses\AdminRole\AdminRoleResponse;
use App\Domains\Admin\Services\AdminRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminRoleController extends BaseController
{
    public function __construct(
        private readonly AdminRoleService $adminRoleService,
    ) {}

    #[OA\Post(path: '/adminRole/search', summary: '查询后台角色列表接口', security: [['bearerAuth' => []]], tags: ['后台角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRoleQueryResponse::class),
        ],
    ))]
    public function search(AdminRoleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminRoleQueryRequest::getId])) {
                $condition[] = [AdminRoleEntity::getId, '=', $requestData[AdminRoleQueryRequest::getId]];
            }
            if (isset($requestData[AdminRoleQueryRequest::getCode])) {
                $condition[] = [AdminRoleEntity::getCode, '=', $requestData[AdminRoleQueryRequest::getCode]];
            }

            $result = $this->adminRoleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdminRoleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdminRoleQueryResponse::from($result);
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

    #[OA\Post(path: '/adminRole/store', summary: '新增后台角色接口', security: [['bearerAuth' => []]], tags: ['后台角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRoleResponse::class),
        ],
    ))]
    public function store(AdminRoleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AdminRoleEntity::from($requestData);

            if ($this->adminRoleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminRole/show', summary: '获取后台角色详情接口', security: [['bearerAuth' => []]], tags: ['后台角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRoleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adminRole = $this->adminRoleService->getOneById($id);
            if (empty($adminRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdminRoleResponse::from($adminRole);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminRole/update', summary: '更新后台角色接口', security: [['bearerAuth' => []]], tags: ['后台角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRoleResponse::class),
        ],
    ))]
    public function update(AdminRoleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminRole = $this->adminRoleService->getOneById($id);
            if (empty($adminRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdminRoleEntity::from($requestData);

            $this->adminRoleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adminRole/destroy', summary: '删除后台角色接口', security: [['bearerAuth' => []]], tags: ['后台角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminRoleDestroyResponse::class),
        ],
    ))]
    public function destroy(AdminRoleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->adminRoleService->removeByIds($requestData['ids'])) {
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
