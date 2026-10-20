<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatBotRuleEntity;
use App\Domains\Chat\Requests\ChatBotRule\ChatBotRuleCreateRequest;
use App\Domains\Chat\Requests\ChatBotRule\ChatBotRuleDestroyRequest;
use App\Domains\Chat\Requests\ChatBotRule\ChatBotRuleQueryRequest;
use App\Domains\Chat\Requests\ChatBotRule\ChatBotRuleUpdateRequest;
use App\Domains\Chat\Responses\ChatBotRule\ChatBotRuleDestroyResponse;
use App\Domains\Chat\Responses\ChatBotRule\ChatBotRuleQueryResponse;
use App\Domains\Chat\Responses\ChatBotRule\ChatBotRuleResponse;
use App\Domains\Chat\Services\ChatBotRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatBotRuleController extends BaseController
{
    public function __construct(
        private readonly ChatBotRuleService $chatBotRuleService,
    ) {}

    #[OA\Post(path: '/chatBotRule/search', summary: '查询机器人自动回复规则列表接口', security: [['bearerAuth' => []]], tags: ['机器人自动回复规则模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatBotRuleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatBotRuleQueryResponse::class),
        ],
    ))]
    public function search(ChatBotRuleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatBotRuleQueryRequest::getId])) {
                $condition[] = [ChatBotRuleEntity::getId, '=', $requestData[ChatBotRuleQueryRequest::getId]];
            }

            $result = $this->chatBotRuleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatBotRuleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatBotRuleQueryResponse::from($result);
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

    #[OA\Post(path: '/chatBotRule/store', summary: '新增机器人自动回复规则接口', security: [['bearerAuth' => []]], tags: ['机器人自动回复规则模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatBotRuleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatBotRuleResponse::class),
        ],
    ))]
    public function store(ChatBotRuleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatBotRuleEntity::from($requestData);

            if ($this->chatBotRuleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatBotRule/show', summary: '获取机器人自动回复规则详情接口', security: [['bearerAuth' => []]], tags: ['机器人自动回复规则模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatBotRuleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatBotRule = $this->chatBotRuleService->getOneById($id);
            if (empty($chatBotRule)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatBotRuleResponse::from($chatBotRule);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatBotRule/update', summary: '更新机器人自动回复规则接口', security: [['bearerAuth' => []]], tags: ['机器人自动回复规则模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatBotRuleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatBotRuleResponse::class),
        ],
    ))]
    public function update(ChatBotRuleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatBotRule = $this->chatBotRuleService->getOneById($id);
            if (empty($chatBotRule)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatBotRuleEntity::from($requestData);

            $this->chatBotRuleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatBotRule/destroy', summary: '删除机器人自动回复规则接口', security: [['bearerAuth' => []]], tags: ['机器人自动回复规则模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatBotRuleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatBotRuleDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatBotRuleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatBotRuleService->removeByIds($requestData['ids'])) {
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
