<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatMessageEntity;
use App\Domains\Chat\Requests\ChatMessage\ChatMessageCreateRequest;
use App\Domains\Chat\Requests\ChatMessage\ChatMessageDestroyRequest;
use App\Domains\Chat\Requests\ChatMessage\ChatMessageQueryRequest;
use App\Domains\Chat\Requests\ChatMessage\ChatMessageUpdateRequest;
use App\Domains\Chat\Responses\ChatMessage\ChatMessageDestroyResponse;
use App\Domains\Chat\Responses\ChatMessage\ChatMessageQueryResponse;
use App\Domains\Chat\Responses\ChatMessage\ChatMessageResponse;
use App\Domains\Chat\Services\ChatMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatMessageController extends BaseController
{
    public function __construct(
        private readonly ChatMessageService $chatMessageService,
    ) {}

    #[OA\Post(path: '/chatMessage/search', summary: '查询客服消息列表接口', security: [['bearerAuth' => []]], tags: ['客服消息模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatMessageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatMessageQueryResponse::class),
        ],
    ))]
    public function search(ChatMessageQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatMessageQueryRequest::getSessionId])) {
                $condition[] = [ChatMessageEntity::getSessionId, '=', $requestData[ChatMessageQueryRequest::getSessionId]];
            }
            if (isset($requestData[ChatMessageQueryRequest::getId])) {
                $condition[] = [ChatMessageEntity::getId, '=', $requestData[ChatMessageQueryRequest::getId]];
            }

            $result = $this->chatMessageService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatMessageResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatMessageQueryResponse::from($result);
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

    #[OA\Post(path: '/chatMessage/store', summary: '新增客服消息接口', security: [['bearerAuth' => []]], tags: ['客服消息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatMessageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatMessageResponse::class),
        ],
    ))]
    public function store(ChatMessageCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatMessageEntity::from($requestData);

            if ($this->chatMessageService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatMessage/show', summary: '获取客服消息详情接口', security: [['bearerAuth' => []]], tags: ['客服消息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatMessageResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatMessage = $this->chatMessageService->getOneById($id);
            if (empty($chatMessage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatMessageResponse::from($chatMessage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatMessage/update', summary: '更新客服消息接口', security: [['bearerAuth' => []]], tags: ['客服消息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatMessageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatMessageResponse::class),
        ],
    ))]
    public function update(ChatMessageUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatMessage = $this->chatMessageService->getOneById($id);
            if (empty($chatMessage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatMessageEntity::from($requestData);

            $this->chatMessageService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatMessage/destroy', summary: '删除客服消息接口', security: [['bearerAuth' => []]], tags: ['客服消息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatMessageDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatMessageDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatMessageDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatMessageService->removeByIds($requestData['ids'])) {
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
