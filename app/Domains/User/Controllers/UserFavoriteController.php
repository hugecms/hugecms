<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserFavoriteEntity;
use App\Domains\User\Requests\UserFavorite\UserFavoriteCreateRequest;
use App\Domains\User\Requests\UserFavorite\UserFavoriteDestroyRequest;
use App\Domains\User\Requests\UserFavorite\UserFavoriteQueryRequest;
use App\Domains\User\Requests\UserFavorite\UserFavoriteUpdateRequest;
use App\Domains\User\Responses\UserFavorite\UserFavoriteDestroyResponse;
use App\Domains\User\Responses\UserFavorite\UserFavoriteQueryResponse;
use App\Domains\User\Responses\UserFavorite\UserFavoriteResponse;
use App\Domains\User\Services\UserFavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserFavoriteController extends BaseController
{
    public function __construct(
        private readonly UserFavoriteService $userFavoriteService,
    ) {}

    #[OA\Post(path: '/userFavorite/search', summary: '查询用户收藏列表接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFavoriteQueryResponse::class),
        ],
    ))]
    public function search(UserFavoriteQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserFavoriteQueryRequest::getTargetType])) {
                $condition[] = [UserFavoriteEntity::getTargetType, '=', $requestData[UserFavoriteQueryRequest::getTargetType]];
            }
            if (isset($requestData[UserFavoriteQueryRequest::getTargetId])) {
                $condition[] = [UserFavoriteEntity::getTargetId, '=', $requestData[UserFavoriteQueryRequest::getTargetId]];
            }
            if (isset($requestData[UserFavoriteQueryRequest::getId])) {
                $condition[] = [UserFavoriteEntity::getId, '=', $requestData[UserFavoriteQueryRequest::getId]];
            }
            if (isset($requestData[UserFavoriteQueryRequest::getUserId])) {
                $condition[] = [UserFavoriteEntity::getUserId, '=', $requestData[UserFavoriteQueryRequest::getUserId]];
            }
            if (isset($requestData[UserFavoriteQueryRequest::getTargetType])) {
                $condition[] = [UserFavoriteEntity::getTargetType, '=', $requestData[UserFavoriteQueryRequest::getTargetType]];
            }
            if (isset($requestData[UserFavoriteQueryRequest::getTargetId])) {
                $condition[] = [UserFavoriteEntity::getTargetId, '=', $requestData[UserFavoriteQueryRequest::getTargetId]];
            }

            $result = $this->userFavoriteService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserFavoriteResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserFavoriteQueryResponse::from($result);
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

    #[OA\Post(path: '/userFavorite/store', summary: '新增用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFavoriteResponse::class),
        ],
    ))]
    public function store(UserFavoriteCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserFavoriteEntity::from($requestData);

            if ($this->userFavoriteService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userFavorite/show', summary: '获取用户收藏详情接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFavoriteResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userFavorite = $this->userFavoriteService->getOneById($id);
            if (empty($userFavorite)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserFavoriteResponse::from($userFavorite);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userFavorite/update', summary: '更新用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFavoriteResponse::class),
        ],
    ))]
    public function update(UserFavoriteUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userFavorite = $this->userFavoriteService->getOneById($id);
            if (empty($userFavorite)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserFavoriteEntity::from($requestData);

            $this->userFavoriteService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userFavorite/destroy', summary: '删除用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFavoriteDestroyResponse::class),
        ],
    ))]
    public function destroy(UserFavoriteDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userFavoriteService->removeByIds($requestData['ids'])) {
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
