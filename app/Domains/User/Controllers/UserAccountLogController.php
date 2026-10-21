<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserAccountLogEntity;
use App\Domains\User\Requests\UserAccountLog\UserAccountLogCreateRequest;
use App\Domains\User\Requests\UserAccountLog\UserAccountLogDestroyRequest;
use App\Domains\User\Requests\UserAccountLog\UserAccountLogQueryRequest;
use App\Domains\User\Requests\UserAccountLog\UserAccountLogUpdateRequest;
use App\Domains\User\Responses\UserAccountLog\UserAccountLogDestroyResponse;
use App\Domains\User\Responses\UserAccountLog\UserAccountLogQueryResponse;
use App\Domains\User\Responses\UserAccountLog\UserAccountLogResponse;
use App\Domains\User\Services\UserAccountLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAccountLogController extends BaseController
{
    public function __construct(
        private readonly UserAccountLogService $userAccountLogService,
    ) {}

    #[OA\Post(path: '/userAccountLog/search', summary: '查询用户资金流水列表接口', security: [['bearerAuth' => []]], tags: ['用户资金流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountLogQueryResponse::class),
        ],
    ))]
    public function search(UserAccountLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAccountLogQueryRequest::getBizNo])) {
                $condition[] = [UserAccountLogEntity::getBizNo, '=', $requestData[UserAccountLogQueryRequest::getBizNo]];
            }
            if (isset($requestData[UserAccountLogQueryRequest::getUserId])) {
                $condition[] = [UserAccountLogEntity::getUserId, '=', $requestData[UserAccountLogQueryRequest::getUserId]];
            }
            if (isset($requestData[UserAccountLogQueryRequest::getCreateTime])) {
                $condition[] = [UserAccountLogEntity::getCreateTime, '=', $requestData[UserAccountLogQueryRequest::getCreateTime]];
            }
            if (isset($requestData[UserAccountLogQueryRequest::getId])) {
                $condition[] = [UserAccountLogEntity::getId, '=', $requestData[UserAccountLogQueryRequest::getId]];
            }

            $result = $this->userAccountLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserAccountLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserAccountLogQueryResponse::from($result);
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

    #[OA\Post(path: '/userAccountLog/store', summary: '新增用户资金流水接口', security: [['bearerAuth' => []]], tags: ['用户资金流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountLogResponse::class),
        ],
    ))]
    public function store(UserAccountLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserAccountLogEntity::from($requestData);

            if ($this->userAccountLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userAccountLog/show', summary: '获取用户资金流水详情接口', security: [['bearerAuth' => []]], tags: ['用户资金流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userAccountLog = $this->userAccountLogService->getOneById($id);
            if (empty($userAccountLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserAccountLogResponse::from($userAccountLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAccountLog/update', summary: '更新用户资金流水接口', security: [['bearerAuth' => []]], tags: ['用户资金流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountLogResponse::class),
        ],
    ))]
    public function update(UserAccountLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAccountLog = $this->userAccountLogService->getOneById($id);
            if (empty($userAccountLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserAccountLogEntity::from($requestData);

            $this->userAccountLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userAccountLog/destroy', summary: '删除用户资金流水接口', security: [['bearerAuth' => []]], tags: ['用户资金流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAccountLogDestroyResponse::class),
        ],
    ))]
    public function destroy(UserAccountLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userAccountLogService->removeByIds($requestData['ids'])) {
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
