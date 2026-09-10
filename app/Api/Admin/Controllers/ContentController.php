<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\ContentEntity;
use App\Services\ContentService;
use App\Api\Admin\Requests\Content\ContentCreateRequest;
use App\Api\Admin\Requests\Content\ContentDestroyRequest;
use App\Api\Admin\Requests\Content\ContentQueryRequest;
use App\Api\Admin\Requests\Content\ContentUpdateRequest;
use App\Api\Admin\Responses\Content\ContentDestroyResponse;
use App\Api\Admin\Responses\Content\ContentQueryResponse;
use App\Api\Admin\Responses\Content\ContentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentController extends BaseController
{
    public function __construct(
        private readonly ContentService $contentService,
    ) {}

    #[OA\Post(path: '/content/search', summary: '查询内容主列表接口', security: [['bearerAuth' => []]], tags: ['内容主模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentQueryResponse::class),
        ],
    ))]
    public function search(ContentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentQueryRequest::getStatus])) {
                $condition[] = [ContentEntity::getStatus, '=', $requestData[ContentQueryRequest::getStatus]];
            }
            if (isset($requestData[ContentQueryRequest::getPublishedAt])) {
                $condition[] = [ContentEntity::getPublishedAt, '=', $requestData[ContentQueryRequest::getPublishedAt]];
            }
            if (isset($requestData[ContentQueryRequest::getPublishedAt])) {
                $condition[] = [ContentEntity::getPublishedAt, '=', $requestData[ContentQueryRequest::getPublishedAt]];
            }
            if (isset($requestData[ContentQueryRequest::getSlug])) {
                $condition[] = [ContentEntity::getSlug, '=', $requestData[ContentQueryRequest::getSlug]];
            }
            if (isset($requestData[ContentQueryRequest::getId])) {
                $condition[] = [ContentEntity::getId, '=', $requestData[ContentQueryRequest::getId]];
            }
            
            $result = $this->contentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentQueryResponse::from($result);
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

    #[OA\Post(path: '/content/store', summary: '新增内容主接口', security: [['bearerAuth' => []]], tags: ['内容主模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentResponse::class),
        ],
    ))]
    public function store(ContentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentEntity::from($requestData);

            if ($this->contentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/content/show', summary: '获取内容主详情接口', security: [['bearerAuth' => []]], tags: ['内容主模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $content = $this->contentService->getOneById($id);
            if (empty($content)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentResponse::from($content);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/content/update', summary: '更新内容主接口', security: [['bearerAuth' => []]], tags: ['内容主模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentResponse::class),
        ],
    ))]
    public function update(ContentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $content = $this->contentService->getOneById($id);
            if (empty($content)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentEntity::from($requestData);

            $this->contentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/content/destroy', summary: '删除内容主接口', security: [['bearerAuth' => []]], tags: ['内容主模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentService->removeByIds($requestData['ids'])) {
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
