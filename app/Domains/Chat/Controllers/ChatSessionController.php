<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatSessionEntity;
use App\Domains\Chat\Requests\ChatSession\ChatSessionCreateRequest;
use App\Domains\Chat\Requests\ChatSession\ChatSessionDestroyRequest;
use App\Domains\Chat\Requests\ChatSession\ChatSessionQueryRequest;
use App\Domains\Chat\Requests\ChatSession\ChatSessionUpdateRequest;
use App\Domains\Chat\Responses\ChatSession\ChatSessionDestroyResponse;
use App\Domains\Chat\Responses\ChatSession\ChatSessionQueryResponse;
use App\Domains\Chat\Responses\ChatSession\ChatSessionResponse;
use App\Domains\Chat\Services\ChatSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatSessionController extends BaseController
{
    public function __construct(
        private readonly ChatSessionService $chatSessionService,
    ) {}

    #[OA\Post(path: '/chatSession/search', summary: '查询客服会话列表接口', security: [['bearerAuth' => []]], tags: ['客服会话模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatSessionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatSessionQueryResponse::class),
        ],
    ))]
    public function search(ChatSessionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatSessionQueryRequest::getServerId])) {
                $condition[] = [ChatSessionEntity::getServerId, '=', $requestData[ChatSessionQueryRequest::getServerId]];
            }
            if (isset($requestData[ChatSessionQueryRequest::getStatus])) {
                $condition[] = [ChatSessionEntity::getStatus, '=', $requestData[ChatSessionQueryRequest::getStatus]];
            }
            if (isset($requestData[ChatSessionQueryRequest::getUpdatedAt])) {
                $condition[] = [ChatSessionEntity::getUpdatedAt, '=', $requestData[ChatSessionQueryRequest::getUpdatedAt]];
            }
            if (isset($requestData[ChatSessionQueryRequest::getUserId])) {
                $condition[] = [ChatSessionEntity::getUserId, '=', $requestData[ChatSessionQueryRequest::getUserId]];
            }
            if (isset($requestData[ChatSessionQueryRequest::getStatus])) {
                $condition[] = [ChatSessionEntity::getStatus, '=', $requestData[ChatSessionQueryRequest::getStatus]];
            }
            if (isset($requestData[ChatSessionQueryRequest::getId])) {
                $condition[] = [ChatSessionEntity::getId, '=', $requestData[ChatSessionQueryRequest::getId]];
            }

            $result = $this->chatSessionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatSessionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatSessionQueryResponse::from($result);
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

    #[OA\Post(path: '/chatSession/store', summary: '新增客服会话接口', security: [['bearerAuth' => []]], tags: ['客服会话模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatSessionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatSessionResponse::class),
        ],
    ))]
    public function store(ChatSessionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatSessionEntity::from($requestData);

            if ($this->chatSessionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatSession/show', summary: '获取客服会话详情接口', security: [['bearerAuth' => []]], tags: ['客服会话模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatSessionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatSession = $this->chatSessionService->getOneById($id);
            if (empty($chatSession)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatSessionResponse::from($chatSession);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatSession/update', summary: '更新客服会话接口', security: [['bearerAuth' => []]], tags: ['客服会话模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatSessionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatSessionResponse::class),
        ],
    ))]
    public function update(ChatSessionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatSession = $this->chatSessionService->getOneById($id);
            if (empty($chatSession)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatSessionEntity::from($requestData);

            $this->chatSessionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatSession/destroy', summary: '删除客服会话接口', security: [['bearerAuth' => []]], tags: ['客服会话模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatSessionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatSessionDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatSessionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatSessionService->removeByIds($requestData['ids'])) {
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
