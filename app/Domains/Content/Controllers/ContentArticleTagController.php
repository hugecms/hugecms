<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentArticleTagEntity;
use App\Domains\Content\Requests\ContentArticleTag\ContentArticleTagCreateRequest;
use App\Domains\Content\Requests\ContentArticleTag\ContentArticleTagDestroyRequest;
use App\Domains\Content\Requests\ContentArticleTag\ContentArticleTagQueryRequest;
use App\Domains\Content\Requests\ContentArticleTag\ContentArticleTagUpdateRequest;
use App\Domains\Content\Responses\ContentArticleTag\ContentArticleTagDestroyResponse;
use App\Domains\Content\Responses\ContentArticleTag\ContentArticleTagQueryResponse;
use App\Domains\Content\Responses\ContentArticleTag\ContentArticleTagResponse;
use App\Domains\Content\Services\ContentArticleTagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentArticleTagController extends BaseController
{
    public function __construct(
        private readonly ContentArticleTagService $contentArticleTagService,
    ) {}

    #[OA\Post(path: '/contentArticleTag/search', summary: '查询文章标签关联列表接口', security: [['bearerAuth' => []]], tags: ['文章标签关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleTagQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleTagQueryResponse::class),
        ],
    ))]
    public function search(ContentArticleTagQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentArticleTagQueryRequest::getTagId])) {
                $condition[] = [ContentArticleTagEntity::getTagId, '=', $requestData[ContentArticleTagQueryRequest::getTagId]];
            }
            if (isset($requestData[ContentArticleTagQueryRequest::getArticleId])) {
                $condition[] = [ContentArticleTagEntity::getArticleId, '=', $requestData[ContentArticleTagQueryRequest::getArticleId]];
            }
            if (isset($requestData[ContentArticleTagQueryRequest::getTagId])) {
                $condition[] = [ContentArticleTagEntity::getTagId, '=', $requestData[ContentArticleTagQueryRequest::getTagId]];
            }

            $result = $this->contentArticleTagService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentArticleTagResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentArticleTagQueryResponse::from($result);
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

    #[OA\Post(path: '/contentArticleTag/store', summary: '新增文章标签关联接口', security: [['bearerAuth' => []]], tags: ['文章标签关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleTagCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleTagResponse::class),
        ],
    ))]
    public function store(ContentArticleTagCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentArticleTagEntity::from($requestData);

            if ($this->contentArticleTagService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentArticleTag/show', summary: '获取文章标签关联详情接口', security: [['bearerAuth' => []]], tags: ['文章标签关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleTagResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentArticleTag = $this->contentArticleTagService->getOneById($id);
            if (empty($contentArticleTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentArticleTagResponse::from($contentArticleTag);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentArticleTag/update', summary: '更新文章标签关联接口', security: [['bearerAuth' => []]], tags: ['文章标签关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleTagUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleTagResponse::class),
        ],
    ))]
    public function update(ContentArticleTagUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentArticleTag = $this->contentArticleTagService->getOneById($id);
            if (empty($contentArticleTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentArticleTagEntity::from($requestData);

            $this->contentArticleTagService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentArticleTag/destroy', summary: '删除文章标签关联接口', security: [['bearerAuth' => []]], tags: ['文章标签关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleTagDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleTagDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentArticleTagDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentArticleTagService->removeByIds($requestData['ids'])) {
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
