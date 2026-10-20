<?php

declare(strict_types=1);

namespace App\Domains\Chat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Chat\Entities\ChatFaqEntity;
use App\Domains\Chat\Requests\ChatFaq\ChatFaqCreateRequest;
use App\Domains\Chat\Requests\ChatFaq\ChatFaqDestroyRequest;
use App\Domains\Chat\Requests\ChatFaq\ChatFaqQueryRequest;
use App\Domains\Chat\Requests\ChatFaq\ChatFaqUpdateRequest;
use App\Domains\Chat\Responses\ChatFaq\ChatFaqDestroyResponse;
use App\Domains\Chat\Responses\ChatFaq\ChatFaqQueryResponse;
use App\Domains\Chat\Responses\ChatFaq\ChatFaqResponse;
use App\Domains\Chat\Services\ChatFaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ChatFaqController extends BaseController
{
    public function __construct(
        private readonly ChatFaqService $chatFaqService,
    ) {}

    #[OA\Post(path: '/chatFaq/search', summary: '查询常见问题列表接口', security: [['bearerAuth' => []]], tags: ['常见问题模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatFaqQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatFaqQueryResponse::class),
        ],
    ))]
    public function search(ChatFaqQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ChatFaqQueryRequest::getCategory])) {
                $condition[] = [ChatFaqEntity::getCategory, '=', $requestData[ChatFaqQueryRequest::getCategory]];
            }
            if (isset($requestData[ChatFaqQueryRequest::getId])) {
                $condition[] = [ChatFaqEntity::getId, '=', $requestData[ChatFaqQueryRequest::getId]];
            }

            $result = $this->chatFaqService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ChatFaqResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ChatFaqQueryResponse::from($result);
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

    #[OA\Post(path: '/chatFaq/store', summary: '新增常见问题接口', security: [['bearerAuth' => []]], tags: ['常见问题模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatFaqCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatFaqResponse::class),
        ],
    ))]
    public function store(ChatFaqCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ChatFaqEntity::from($requestData);

            if ($this->chatFaqService->save($input->toEntity())) {
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

    #[OA\Get(path: '/chatFaq/show', summary: '获取常见问题详情接口', security: [['bearerAuth' => []]], tags: ['常见问题模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatFaqResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $chatFaq = $this->chatFaqService->getOneById($id);
            if (empty($chatFaq)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ChatFaqResponse::from($chatFaq);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/chatFaq/update', summary: '更新常见问题接口', security: [['bearerAuth' => []]], tags: ['常见问题模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatFaqUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatFaqResponse::class),
        ],
    ))]
    public function update(ChatFaqUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $chatFaq = $this->chatFaqService->getOneById($id);
            if (empty($chatFaq)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ChatFaqEntity::from($requestData);

            $this->chatFaqService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/chatFaq/destroy', summary: '删除常见问题接口', security: [['bearerAuth' => []]], tags: ['常见问题模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ChatFaqDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ChatFaqDestroyResponse::class),
        ],
    ))]
    public function destroy(ChatFaqDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->chatFaqService->removeByIds($requestData['ids'])) {
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
