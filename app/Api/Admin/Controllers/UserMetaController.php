<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\UserMeta\UserMetaCreateRequest;
use App\Api\Admin\Requests\UserMeta\UserMetaDestroyRequest;
use App\Api\Admin\Requests\UserMeta\UserMetaQueryRequest;
use App\Api\Admin\Requests\UserMeta\UserMetaUpdateRequest;
use App\Api\Admin\Responses\UserMeta\UserMetaDestroyResponse;
use App\Api\Admin\Responses\UserMeta\UserMetaQueryResponse;
use App\Api\Admin\Responses\UserMeta\UserMetaResponse;
use App\Entities\UserMetaEntity;
use App\Services\UserMetaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserMetaController extends BaseController
{
    public function __construct(
        private readonly UserMetaService $userMetaService,
    ) {}

    #[OA\Post(path: '/userMeta/search', summary: '查询用户元数据列表接口', security: [['bearerAuth' => []]], tags: ['用户元数据模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserMetaQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserMetaQueryResponse::class),
        ],
    ))]
    public function search(UserMetaQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[UserMetaQueryRequest::getId])) {
                $condition[] = [UserMetaEntity::getId, '=', $requestData[UserMetaQueryRequest::getId]];
            }
            if (isset($requestData[UserMetaQueryRequest::getMetaKey])) {
                $condition[] = [UserMetaEntity::getMetaKey, '=', $requestData[UserMetaQueryRequest::getMetaKey]];
            }

            $result = $this->userMetaService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserMetaResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserMetaQueryResponse::from($result);
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

    #[OA\Post(path: '/userMeta/store', summary: '新增用户元数据接口', security: [['bearerAuth' => []]], tags: ['用户元数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserMetaCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserMetaResponse::class),
        ],
    ))]
    public function store(UserMetaCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = UserMetaEntity::from($requestData);

            if ($this->userMetaService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userMeta/show', summary: '获取用户元数据详情接口', security: [['bearerAuth' => []]], tags: ['用户元数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserMetaResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userMeta = $this->userMetaService->getOneById($id);
            if (empty($userMeta)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserMetaResponse::from($userMeta);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userMeta/update', summary: '更新用户元数据接口', security: [['bearerAuth' => []]], tags: ['用户元数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserMetaUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserMetaResponse::class),
        ],
    ))]
    public function update(UserMetaUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $userMeta = $this->userMetaService->getOneById($id);
            if (empty($userMeta)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserMetaEntity::from($requestData);

            $this->userMetaService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userMeta/destroy', summary: '删除用户元数据接口', security: [['bearerAuth' => []]], tags: ['用户元数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserMetaDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserMetaDestroyResponse::class),
        ],
    ))]
    public function destroy(UserMetaDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->userMetaService->removeByIds($requestData['ids'])) {
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
