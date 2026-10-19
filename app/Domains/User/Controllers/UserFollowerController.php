<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserFollowerEntity;
use App\Domains\User\Services\UserFollowerService;
use App\Domains\User\Requests\UserFollower\UserFollowerCreateRequest;
use App\Domains\User\Requests\UserFollower\UserFollowerDestroyRequest;
use App\Domains\User\Requests\UserFollower\UserFollowerQueryRequest;
use App\Domains\User\Requests\UserFollower\UserFollowerUpdateRequest;
use App\Domains\User\Responses\UserFollower\UserFollowerDestroyResponse;
use App\Domains\User\Responses\UserFollower\UserFollowerQueryResponse;
use App\Domains\User\Responses\UserFollower\UserFollowerResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserFollowerController extends BaseController
{
    public function __construct(
        private readonly UserFollowerService $userFollowerService,
    ) {}

    #[OA\Post(path: '/userFollower/search', summary: '查询关注关系列表接口', security: [['bearerAuth' => []]], tags: ['关注关系模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFollowerQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFollowerQueryResponse::class),
        ],
    ))]
    public function search(UserFollowerQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserFollowerQueryRequest::getFollowerId])) {
                $condition[] = [UserFollowerEntity::getFollowerId, '=', $requestData[UserFollowerQueryRequest::getFollowerId]];
            }
            if (isset($requestData[UserFollowerQueryRequest::getFollowerId])) {
                $condition[] = [UserFollowerEntity::getFollowerId, '=', $requestData[UserFollowerQueryRequest::getFollowerId]];
            }
            
            $result = $this->userFollowerService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserFollowerResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserFollowerQueryResponse::from($result);
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

    #[OA\Post(path: '/userFollower/store', summary: '新增关注关系接口', security: [['bearerAuth' => []]], tags: ['关注关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFollowerCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFollowerResponse::class),
        ],
    ))]
    public function store(UserFollowerCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserFollowerEntity::from($requestData);

            if ($this->userFollowerService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userFollower/show', summary: '获取关注关系详情接口', security: [['bearerAuth' => []]], tags: ['关注关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFollowerResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userFollower = $this->userFollowerService->getOneById($id);
            if (empty($userFollower)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserFollowerResponse::from($userFollower);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userFollower/update', summary: '更新关注关系接口', security: [['bearerAuth' => []]], tags: ['关注关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFollowerUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFollowerResponse::class),
        ],
    ))]
    public function update(UserFollowerUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userFollower = $this->userFollowerService->getOneById($id);
            if (empty($userFollower)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserFollowerEntity::from($requestData);

            $this->userFollowerService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userFollower/destroy', summary: '删除关注关系接口', security: [['bearerAuth' => []]], tags: ['关注关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFollowerDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFollowerDestroyResponse::class),
        ],
    ))]
    public function destroy(UserFollowerDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userFollowerService->removeByIds($requestData['ids'])) {
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
