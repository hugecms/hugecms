<?php

declare(strict_types=1);

namespace App\Domains\Notification\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Notification\Entities\NotificationEntity;
use App\Domains\Notification\Requests\Notification\NotificationCreateRequest;
use App\Domains\Notification\Requests\Notification\NotificationDestroyRequest;
use App\Domains\Notification\Requests\Notification\NotificationQueryRequest;
use App\Domains\Notification\Requests\Notification\NotificationUpdateRequest;
use App\Domains\Notification\Responses\Notification\NotificationDestroyResponse;
use App\Domains\Notification\Responses\Notification\NotificationQueryResponse;
use App\Domains\Notification\Responses\Notification\NotificationResponse;
use App\Domains\Notification\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class NotificationController extends BaseController
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    #[OA\Post(path: '/notification/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NotificationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NotificationQueryResponse::class),
        ],
    ))]
    public function search(NotificationQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[NotificationQueryRequest::getNotifiableType])) {
                $condition[] = [NotificationEntity::getNotifiableType, '=', $requestData[NotificationQueryRequest::getNotifiableType]];
            }
            if (isset($requestData[NotificationQueryRequest::getNotifiableId])) {
                $condition[] = [NotificationEntity::getNotifiableId, '=', $requestData[NotificationQueryRequest::getNotifiableId]];
            }
            if (isset($requestData[NotificationQueryRequest::getId])) {
                $condition[] = [NotificationEntity::getId, '=', $requestData[NotificationQueryRequest::getId]];
            }

            $result = $this->notificationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = NotificationResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = NotificationQueryResponse::from($result);
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

    #[OA\Post(path: '/notification/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NotificationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NotificationResponse::class),
        ],
    ))]
    public function store(NotificationCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = NotificationEntity::from($requestData);

            if ($this->notificationService->save($input->toEntity())) {
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

    #[OA\Get(path: '/notification/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NotificationResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $notification = $this->notificationService->getOneById($id);
            if (empty($notification)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = NotificationResponse::from($notification);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/notification/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NotificationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NotificationResponse::class),
        ],
    ))]
    public function update(NotificationUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $notification = $this->notificationService->getOneById($id);
            if (empty($notification)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = NotificationEntity::from($requestData);

            $this->notificationService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/notification/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NotificationDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NotificationDestroyResponse::class),
        ],
    ))]
    public function destroy(NotificationDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->notificationService->removeByIds($requestData['ids'])) {
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
