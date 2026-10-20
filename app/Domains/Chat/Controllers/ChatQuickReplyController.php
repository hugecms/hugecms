<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatQuickReplyEntity;
use App\Domains\Chat\Requests\ChatQuickReply\ChatQuickReplyCreateRequest;
use App\Domains\Chat\Requests\ChatQuickReply\ChatQuickReplyDestroyRequest;
use App\Domains\Chat\Requests\ChatQuickReply\ChatQuickReplyQueryRequest;
use App\Domains\Chat\Requests\ChatQuickReply\ChatQuickReplyUpdateRequest;
use App\Domains\Chat\Responses\ChatQuickReply\ChatQuickReplyDestroyResponse;
use App\Domains\Chat\Responses\ChatQuickReply\ChatQuickReplyQueryResponse;
use App\Domains\Chat\Responses\ChatQuickReply\ChatQuickReplyResponse;
use App\Domains\Chat\Services\ChatQuickReplyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatQuickReplyController extends BaseController
{
    public function __construct(
        private readonly ChatQuickReplyService $chatQuickReplyService,
    ) {}

    #[OA\Post(path: '/chatQuickReply/search', summary: '查询客服话术库列表接口', security: [['bearerAuth' => []]], tags: ['客服话术库模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatQuickReplyQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatQuickReplyQueryResponse::class),
        ],
    ))]
    public function search(ChatQuickReplyQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatQuickReplyQueryRequest::getGroup])) {
                $condition[] = [ChatQuickReplyEntity::getGroup, '=', $requestData[ChatQuickReplyQueryRequest::getGroup]];
            }
            if (isset($requestData[ChatQuickReplyQueryRequest::getId])) {
                $condition[] = [ChatQuickReplyEntity::getId, '=', $requestData[ChatQuickReplyQueryRequest::getId]];
            }

            $result = $this->chatQuickReplyService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatQuickReplyResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatQuickReplyQueryResponse::from($result);
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

    #[OA\Post(path: '/chatQuickReply/store', summary: '新增客服话术库接口', security: [['bearerAuth' => []]], tags: ['客服话术库模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatQuickReplyCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatQuickReplyResponse::class),
        ],
    ))]
    public function store(ChatQuickReplyCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatQuickReplyEntity::from($requestData);

            if ($this->chatQuickReplyService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatQuickReply/show', summary: '获取客服话术库详情接口', security: [['bearerAuth' => []]], tags: ['客服话术库模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatQuickReplyResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatQuickReply = $this->chatQuickReplyService->getOneById($id);
            if (empty($chatQuickReply)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatQuickReplyResponse::from($chatQuickReply);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatQuickReply/update', summary: '更新客服话术库接口', security: [['bearerAuth' => []]], tags: ['客服话术库模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatQuickReplyUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatQuickReplyResponse::class),
        ],
    ))]
    public function update(ChatQuickReplyUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatQuickReply = $this->chatQuickReplyService->getOneById($id);
            if (empty($chatQuickReply)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatQuickReplyEntity::from($requestData);

            $this->chatQuickReplyService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatQuickReply/destroy', summary: '删除客服话术库接口', security: [['bearerAuth' => []]], tags: ['客服话术库模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatQuickReplyDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatQuickReplyDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatQuickReplyDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatQuickReplyService->removeByIds($requestData['ids'])) {
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
