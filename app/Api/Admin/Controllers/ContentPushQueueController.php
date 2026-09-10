<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\ContentPushQueue\ContentPushQueueCreateRequest;
use App\Api\Admin\Requests\ContentPushQueue\ContentPushQueueDestroyRequest;
use App\Api\Admin\Requests\ContentPushQueue\ContentPushQueueQueryRequest;
use App\Api\Admin\Requests\ContentPushQueue\ContentPushQueueUpdateRequest;
use App\Api\Admin\Responses\ContentPushQueue\ContentPushQueueDestroyResponse;
use App\Api\Admin\Responses\ContentPushQueue\ContentPushQueueQueryResponse;
use App\Api\Admin\Responses\ContentPushQueue\ContentPushQueueResponse;
use App\Entities\ContentPushQueueEntity;
use App\Services\ContentPushQueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentPushQueueController extends BaseController
{
    public function __construct(
        private readonly ContentPushQueueService $contentPushQueueService,
    ) {}

    #[OA\Post(path: '/contentPushQueue/search', summary: '查询内容推送记录列表接口', security: [['bearerAuth' => []]], tags: ['内容推送记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPushQueueQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPushQueueQueryResponse::class),
        ],
    ))]
    public function search(ContentPushQueueQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[ContentPushQueueQueryRequest::getContentId])) {
                $condition[] = [ContentPushQueueEntity::getContentId, '=', $requestData[ContentPushQueueQueryRequest::getContentId]];
            }
            if (isset($requestData[ContentPushQueueQueryRequest::getStatus])) {
                $condition[] = [ContentPushQueueEntity::getStatus, '=', $requestData[ContentPushQueueQueryRequest::getStatus]];
            }
            if (isset($requestData[ContentPushQueueQueryRequest::getId])) {
                $condition[] = [ContentPushQueueEntity::getId, '=', $requestData[ContentPushQueueQueryRequest::getId]];
            }

            $result = $this->contentPushQueueService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentPushQueueResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentPushQueueQueryResponse::from($result);
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

    #[OA\Post(path: '/contentPushQueue/store', summary: '新增内容推送记录接口', security: [['bearerAuth' => []]], tags: ['内容推送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPushQueueCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPushQueueResponse::class),
        ],
    ))]
    public function store(ContentPushQueueCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = ContentPushQueueEntity::from($requestData);

            if ($this->contentPushQueueService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentPushQueue/show', summary: '获取内容推送记录详情接口', security: [['bearerAuth' => []]], tags: ['内容推送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPushQueueResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentPushQueue = $this->contentPushQueueService->getOneById($id);
            if (empty($contentPushQueue)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentPushQueueResponse::from($contentPushQueue);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentPushQueue/update', summary: '更新内容推送记录接口', security: [['bearerAuth' => []]], tags: ['内容推送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPushQueueUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPushQueueResponse::class),
        ],
    ))]
    public function update(ContentPushQueueUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $contentPushQueue = $this->contentPushQueueService->getOneById($id);
            if (empty($contentPushQueue)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentPushQueueEntity::from($requestData);

            $this->contentPushQueueService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentPushQueue/destroy', summary: '删除内容推送记录接口', security: [['bearerAuth' => []]], tags: ['内容推送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPushQueueDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPushQueueDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentPushQueueDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->contentPushQueueService->removeByIds($requestData['ids'])) {
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
