<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserBrowsingHistoryEntity;
use App\Domains\User\Requests\UserBrowsingHistory\UserBrowsingHistoryCreateRequest;
use App\Domains\User\Requests\UserBrowsingHistory\UserBrowsingHistoryDestroyRequest;
use App\Domains\User\Requests\UserBrowsingHistory\UserBrowsingHistoryQueryRequest;
use App\Domains\User\Requests\UserBrowsingHistory\UserBrowsingHistoryUpdateRequest;
use App\Domains\User\Responses\UserBrowsingHistory\UserBrowsingHistoryDestroyResponse;
use App\Domains\User\Responses\UserBrowsingHistory\UserBrowsingHistoryQueryResponse;
use App\Domains\User\Responses\UserBrowsingHistory\UserBrowsingHistoryResponse;
use App\Domains\User\Services\UserBrowsingHistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserBrowsingHistoryController extends BaseController
{
    public function __construct(
        private readonly UserBrowsingHistoryService $userBrowsingHistoryService,
    ) {}

    #[OA\Post(path: '/userBrowsingHistory/search', summary: '查询用户浏览足迹列表接口', security: [['bearerAuth' => []]], tags: ['用户浏览足迹模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBrowsingHistoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserBrowsingHistoryQueryResponse::class),
        ],
    ))]
    public function search(UserBrowsingHistoryQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserBrowsingHistoryQueryRequest::getCreateTime])) {
                $condition[] = [UserBrowsingHistoryEntity::getCreateTime, '=', $requestData[UserBrowsingHistoryQueryRequest::getCreateTime]];
            }
            if (isset($requestData[UserBrowsingHistoryQueryRequest::getId])) {
                $condition[] = [UserBrowsingHistoryEntity::getId, '=', $requestData[UserBrowsingHistoryQueryRequest::getId]];
            }
            if (isset($requestData[UserBrowsingHistoryQueryRequest::getUserId])) {
                $condition[] = [UserBrowsingHistoryEntity::getUserId, '=', $requestData[UserBrowsingHistoryQueryRequest::getUserId]];
            }
            if (isset($requestData[UserBrowsingHistoryQueryRequest::getTargetType])) {
                $condition[] = [UserBrowsingHistoryEntity::getTargetType, '=', $requestData[UserBrowsingHistoryQueryRequest::getTargetType]];
            }
            if (isset($requestData[UserBrowsingHistoryQueryRequest::getTargetId])) {
                $condition[] = [UserBrowsingHistoryEntity::getTargetId, '=', $requestData[UserBrowsingHistoryQueryRequest::getTargetId]];
            }

            $result = $this->userBrowsingHistoryService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserBrowsingHistoryResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserBrowsingHistoryQueryResponse::from($result);
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

    #[OA\Post(path: '/userBrowsingHistory/store', summary: '新增用户浏览足迹接口', security: [['bearerAuth' => []]], tags: ['用户浏览足迹模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBrowsingHistoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserBrowsingHistoryResponse::class),
        ],
    ))]
    public function store(UserBrowsingHistoryCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserBrowsingHistoryEntity::from($requestData);

            if ($this->userBrowsingHistoryService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userBrowsingHistory/show', summary: '获取用户浏览足迹详情接口', security: [['bearerAuth' => []]], tags: ['用户浏览足迹模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserBrowsingHistoryResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userBrowsingHistory = $this->userBrowsingHistoryService->getOneById($id);
            if (empty($userBrowsingHistory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserBrowsingHistoryResponse::from($userBrowsingHistory);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userBrowsingHistory/update', summary: '更新用户浏览足迹接口', security: [['bearerAuth' => []]], tags: ['用户浏览足迹模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBrowsingHistoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserBrowsingHistoryResponse::class),
        ],
    ))]
    public function update(UserBrowsingHistoryUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userBrowsingHistory = $this->userBrowsingHistoryService->getOneById($id);
            if (empty($userBrowsingHistory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserBrowsingHistoryEntity::from($requestData);

            $this->userBrowsingHistoryService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userBrowsingHistory/destroy', summary: '删除用户浏览足迹接口', security: [['bearerAuth' => []]], tags: ['用户浏览足迹模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBrowsingHistoryDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserBrowsingHistoryDestroyResponse::class),
        ],
    ))]
    public function destroy(UserBrowsingHistoryDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userBrowsingHistoryService->removeByIds($requestData['ids'])) {
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
