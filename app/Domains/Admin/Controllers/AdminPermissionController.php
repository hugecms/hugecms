<?php

declare(strict_types=1);

namespace App\Domains\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Admin\Entities\AdminPermissionEntity;
use App\Domains\Admin\Requests\AdminPermission\AdminPermissionCreateRequest;
use App\Domains\Admin\Requests\AdminPermission\AdminPermissionDestroyRequest;
use App\Domains\Admin\Requests\AdminPermission\AdminPermissionQueryRequest;
use App\Domains\Admin\Requests\AdminPermission\AdminPermissionUpdateRequest;
use App\Domains\Admin\Responses\AdminPermission\AdminPermissionDestroyResponse;
use App\Domains\Admin\Responses\AdminPermission\AdminPermissionQueryResponse;
use App\Domains\Admin\Responses\AdminPermission\AdminPermissionResponse;
use App\Domains\Admin\Services\AdminPermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminPermissionController extends BaseController
{
    public function __construct(
        private readonly AdminPermissionService $adminPermissionService,
    ) {}

    #[OA\Post(path: '/adminPermission/search', summary: '查询后台权限列表接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminPermissionQueryResponse::class),
        ],
    ))]
    public function search(AdminPermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminPermissionQueryRequest::getParentId])) {
                $condition[] = [AdminPermissionEntity::getParentId, '=', $requestData[AdminPermissionQueryRequest::getParentId]];
            }
            if (isset($requestData[AdminPermissionQueryRequest::getId])) {
                $condition[] = [AdminPermissionEntity::getId, '=', $requestData[AdminPermissionQueryRequest::getId]];
            }
            if (isset($requestData[AdminPermissionQueryRequest::getCode])) {
                $condition[] = [AdminPermissionEntity::getCode, '=', $requestData[AdminPermissionQueryRequest::getCode]];
            }

            $result = $this->adminPermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdminPermissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdminPermissionQueryResponse::from($result);
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

    #[OA\Post(path: '/adminPermission/store', summary: '新增后台权限接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminPermissionResponse::class),
        ],
    ))]
    public function store(AdminPermissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AdminPermissionEntity::from($requestData);

            if ($this->adminPermissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminPermission/show', summary: '获取后台权限详情接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminPermissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adminPermission = $this->adminPermissionService->getOneById($id);
            if (empty($adminPermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdminPermissionResponse::from($adminPermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminPermission/update', summary: '更新后台权限接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminPermissionResponse::class),
        ],
    ))]
    public function update(AdminPermissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminPermission = $this->adminPermissionService->getOneById($id);
            if (empty($adminPermission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdminPermissionEntity::from($requestData);

            $this->adminPermissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adminPermission/destroy', summary: '删除后台权限接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdminPermissionDestroyResponse::class),
        ],
    ))]
    public function destroy(AdminPermissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->adminPermissionService->removeByIds($requestData['ids'])) {
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
