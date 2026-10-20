<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatServiceEntity;
use App\Domains\Chat\Requests\ChatService\ChatServiceCreateRequest;
use App\Domains\Chat\Requests\ChatService\ChatServiceDestroyRequest;
use App\Domains\Chat\Requests\ChatService\ChatServiceQueryRequest;
use App\Domains\Chat\Requests\ChatService\ChatServiceUpdateRequest;
use App\Domains\Chat\Responses\ChatService\ChatServiceDestroyResponse;
use App\Domains\Chat\Responses\ChatService\ChatServiceQueryResponse;
use App\Domains\Chat\Responses\ChatService\ChatServiceResponse;
use App\Domains\Chat\Services\ChatServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatServiceController extends BaseController
{
    public function __construct(
        private readonly ChatServiceService $chatServiceService,
    ) {}

    #[OA\Post(path: '/chatService/search', summary: '查询客服工作状态列表接口', security: [['bearerAuth' => []]], tags: ['客服工作状态模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatServiceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatServiceQueryResponse::class),
        ],
    ))]
    public function search(ChatServiceQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatServiceQueryRequest::getId])) {
                $condition[] = [ChatServiceEntity::getId, '=', $requestData[ChatServiceQueryRequest::getId]];
            }
            if (isset($requestData[ChatServiceQueryRequest::getAdminUserId])) {
                $condition[] = [ChatServiceEntity::getAdminUserId, '=', $requestData[ChatServiceQueryRequest::getAdminUserId]];
            }

            $result = $this->chatServiceService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatServiceResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatServiceQueryResponse::from($result);
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

    #[OA\Post(path: '/chatService/store', summary: '新增客服工作状态接口', security: [['bearerAuth' => []]], tags: ['客服工作状态模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatServiceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatServiceResponse::class),
        ],
    ))]
    public function store(ChatServiceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatServiceEntity::from($requestData);

            if ($this->chatServiceService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatService/show', summary: '获取客服工作状态详情接口', security: [['bearerAuth' => []]], tags: ['客服工作状态模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatServiceResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatService = $this->chatServiceService->getOneById($id);
            if (empty($chatService)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatServiceResponse::from($chatService);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatService/update', summary: '更新客服工作状态接口', security: [['bearerAuth' => []]], tags: ['客服工作状态模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatServiceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatServiceResponse::class),
        ],
    ))]
    public function update(ChatServiceUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatService = $this->chatServiceService->getOneById($id);
            if (empty($chatService)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatServiceEntity::from($requestData);

            $this->chatServiceService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatService/destroy', summary: '删除客服工作状态接口', security: [['bearerAuth' => []]], tags: ['客服工作状态模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatServiceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatServiceDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatServiceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatServiceService->removeByIds($requestData['ids'])) {
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
