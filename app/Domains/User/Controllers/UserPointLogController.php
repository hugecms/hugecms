<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserPointLogEntity;
use App\Domains\User\Requests\UserPointLog\UserPointLogCreateRequest;
use App\Domains\User\Requests\UserPointLog\UserPointLogDestroyRequest;
use App\Domains\User\Requests\UserPointLog\UserPointLogQueryRequest;
use App\Domains\User\Requests\UserPointLog\UserPointLogUpdateRequest;
use App\Domains\User\Responses\UserPointLog\UserPointLogDestroyResponse;
use App\Domains\User\Responses\UserPointLog\UserPointLogQueryResponse;
use App\Domains\User\Responses\UserPointLog\UserPointLogResponse;
use App\Domains\User\Services\UserPointLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserPointLogController extends BaseController
{
    public function __construct(
        private readonly UserPointLogService $userPointLogService,
    ) {}

    #[OA\Post(path: '/userPointLog/search', summary: '查询用户积分流水列表接口', security: [['bearerAuth' => []]], tags: ['用户积分流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPointLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserPointLogQueryResponse::class),
        ],
    ))]
    public function search(UserPointLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserPointLogQueryRequest::getUserId])) {
                $condition[] = [UserPointLogEntity::getUserId, '=', $requestData[UserPointLogQueryRequest::getUserId]];
            }
            if (isset($requestData[UserPointLogQueryRequest::getCreateTime])) {
                $condition[] = [UserPointLogEntity::getCreateTime, '=', $requestData[UserPointLogQueryRequest::getCreateTime]];
            }
            if (isset($requestData[UserPointLogQueryRequest::getId])) {
                $condition[] = [UserPointLogEntity::getId, '=', $requestData[UserPointLogQueryRequest::getId]];
            }

            $result = $this->userPointLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserPointLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserPointLogQueryResponse::from($result);
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

    #[OA\Post(path: '/userPointLog/store', summary: '新增用户积分流水接口', security: [['bearerAuth' => []]], tags: ['用户积分流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPointLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserPointLogResponse::class),
        ],
    ))]
    public function store(UserPointLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserPointLogEntity::from($requestData);

            if ($this->userPointLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userPointLog/show', summary: '获取用户积分流水详情接口', security: [['bearerAuth' => []]], tags: ['用户积分流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserPointLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userPointLog = $this->userPointLogService->getOneById($id);
            if (empty($userPointLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserPointLogResponse::from($userPointLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userPointLog/update', summary: '更新用户积分流水接口', security: [['bearerAuth' => []]], tags: ['用户积分流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPointLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserPointLogResponse::class),
        ],
    ))]
    public function update(UserPointLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userPointLog = $this->userPointLogService->getOneById($id);
            if (empty($userPointLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserPointLogEntity::from($requestData);

            $this->userPointLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userPointLog/destroy', summary: '删除用户积分流水接口', security: [['bearerAuth' => []]], tags: ['用户积分流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPointLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserPointLogDestroyResponse::class),
        ],
    ))]
    public function destroy(UserPointLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userPointLogService->removeByIds($requestData['ids'])) {
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
