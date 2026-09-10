<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\UserRoleEntity;
use App\Services\UserRoleService;
use App\Api\Admin\Requests\UserRole\UserRoleCreateRequest;
use App\Api\Admin\Requests\UserRole\UserRoleDestroyRequest;
use App\Api\Admin\Requests\UserRole\UserRoleQueryRequest;
use App\Api\Admin\Requests\UserRole\UserRoleUpdateRequest;
use App\Api\Admin\Responses\UserRole\UserRoleDestroyResponse;
use App\Api\Admin\Responses\UserRole\UserRoleQueryResponse;
use App\Api\Admin\Responses\UserRole\UserRoleResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserRoleController extends BaseController
{
    public function __construct(
        private readonly UserRoleService $userRoleService,
    ) {}

    #[OA\Post(path: '/userRole/search', summary: '查询用户角色关联列表接口', security: [['bearerAuth' => []]], tags: ['用户角色关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRoleQueryResponse::class),
        ],
    ))]
    public function search(UserRoleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserRoleQueryRequest::getId])) {
                $condition[] = [UserRoleEntity::getId, '=', $requestData[UserRoleQueryRequest::getId]];
            }
            if (isset($requestData[UserRoleQueryRequest::getRoleId])) {
                $condition[] = [UserRoleEntity::getRoleId, '=', $requestData[UserRoleQueryRequest::getRoleId]];
            }
            if (isset($requestData[UserRoleQueryRequest::getRoleId])) {
                $condition[] = [UserRoleEntity::getRoleId, '=', $requestData[UserRoleQueryRequest::getRoleId]];
            }
            
            $result = $this->userRoleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserRoleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserRoleQueryResponse::from($result);
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

    #[OA\Post(path: '/userRole/store', summary: '新增用户角色关联接口', security: [['bearerAuth' => []]], tags: ['用户角色关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRoleResponse::class),
        ],
    ))]
    public function store(UserRoleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserRoleEntity::from($requestData);

            if ($this->userRoleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userRole/show', summary: '获取用户角色关联详情接口', security: [['bearerAuth' => []]], tags: ['用户角色关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRoleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userRole = $this->userRoleService->getOneById($id);
            if (empty($userRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserRoleResponse::from($userRole);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userRole/update', summary: '更新用户角色关联接口', security: [['bearerAuth' => []]], tags: ['用户角色关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRoleResponse::class),
        ],
    ))]
    public function update(UserRoleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userRole = $this->userRoleService->getOneById($id);
            if (empty($userRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserRoleEntity::from($requestData);

            $this->userRoleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userRole/destroy', summary: '删除用户角色关联接口', security: [['bearerAuth' => []]], tags: ['用户角色关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRoleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRoleDestroyResponse::class),
        ],
    ))]
    public function destroy(UserRoleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userRoleService->removeByIds($requestData['ids'])) {
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
