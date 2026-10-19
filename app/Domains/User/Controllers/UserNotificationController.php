<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserNotificationEntity;
use App\Domains\User\Services\UserNotificationService;
use App\Domains\User\Requests\UserNotification\UserNotificationCreateRequest;
use App\Domains\User\Requests\UserNotification\UserNotificationDestroyRequest;
use App\Domains\User\Requests\UserNotification\UserNotificationQueryRequest;
use App\Domains\User\Requests\UserNotification\UserNotificationUpdateRequest;
use App\Domains\User\Responses\UserNotification\UserNotificationDestroyResponse;
use App\Domains\User\Responses\UserNotification\UserNotificationQueryResponse;
use App\Domains\User\Responses\UserNotification\UserNotificationResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserNotificationController extends BaseController
{
    public function __construct(
        private readonly UserNotificationService $userNotificationService,
    ) {}

    #[OA\Post(path: '/userNotification/search', summary: '查询用户通知列表接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserNotificationQueryResponse::class),
        ],
    ))]
    public function search(UserNotificationQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserNotificationQueryRequest::getReadAt])) {
                $condition[] = [UserNotificationEntity::getReadAt, '=', $requestData[UserNotificationQueryRequest::getReadAt]];
            }
            if (isset($requestData[UserNotificationQueryRequest::getId])) {
                $condition[] = [UserNotificationEntity::getId, '=', $requestData[UserNotificationQueryRequest::getId]];
            }
            
            $result = $this->userNotificationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserNotificationResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserNotificationQueryResponse::from($result);
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

    #[OA\Post(path: '/userNotification/store', summary: '新增用户通知接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserNotificationResponse::class),
        ],
    ))]
    public function store(UserNotificationCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserNotificationEntity::from($requestData);

            if ($this->userNotificationService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userNotification/show', summary: '获取用户通知详情接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserNotificationResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userNotification = $this->userNotificationService->getOneById($id);
            if (empty($userNotification)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserNotificationResponse::from($userNotification);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userNotification/update', summary: '更新用户通知接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserNotificationResponse::class),
        ],
    ))]
    public function update(UserNotificationUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userNotification = $this->userNotificationService->getOneById($id);
            if (empty($userNotification)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserNotificationEntity::from($requestData);

            $this->userNotificationService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userNotification/destroy', summary: '删除用户通知接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserNotificationDestroyResponse::class),
        ],
    ))]
    public function destroy(UserNotificationDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userNotificationService->removeByIds($requestData['ids'])) {
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
