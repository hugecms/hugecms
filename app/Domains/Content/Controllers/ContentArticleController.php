<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentArticleEntity;
use App\Domains\Content\Requests\ContentArticle\ContentArticleCreateRequest;
use App\Domains\Content\Requests\ContentArticle\ContentArticleDestroyRequest;
use App\Domains\Content\Requests\ContentArticle\ContentArticleQueryRequest;
use App\Domains\Content\Requests\ContentArticle\ContentArticleUpdateRequest;
use App\Domains\Content\Responses\ContentArticle\ContentArticleDestroyResponse;
use App\Domains\Content\Responses\ContentArticle\ContentArticleQueryResponse;
use App\Domains\Content\Responses\ContentArticle\ContentArticleResponse;
use App\Domains\Content\Services\ContentArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentArticleController extends BaseController
{
    public function __construct(
        private readonly ContentArticleService $contentArticleService,
    ) {}

    #[OA\Post(path: '/contentArticle/search', summary: '查询文章列表接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleQueryResponse::class),
        ],
    ))]
    public function search(ContentArticleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentArticleQueryRequest::getCategoryId])) {
                $condition[] = [ContentArticleEntity::getCategoryId, '=', $requestData[ContentArticleQueryRequest::getCategoryId]];
            }
            if (isset($requestData[ContentArticleQueryRequest::getStatus])) {
                $condition[] = [ContentArticleEntity::getStatus, '=', $requestData[ContentArticleQueryRequest::getStatus]];
            }
            if (isset($requestData[ContentArticleQueryRequest::getPublishedAt])) {
                $condition[] = [ContentArticleEntity::getPublishedAt, '=', $requestData[ContentArticleQueryRequest::getPublishedAt]];
            }
            if (isset($requestData[ContentArticleQueryRequest::getId])) {
                $condition[] = [ContentArticleEntity::getId, '=', $requestData[ContentArticleQueryRequest::getId]];
            }
            if (isset($requestData[ContentArticleQueryRequest::getSlug])) {
                $condition[] = [ContentArticleEntity::getSlug, '=', $requestData[ContentArticleQueryRequest::getSlug]];
            }

            $result = $this->contentArticleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentArticleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentArticleQueryResponse::from($result);
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

    #[OA\Post(path: '/contentArticle/store', summary: '新增文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleResponse::class),
        ],
    ))]
    public function store(ContentArticleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentArticleEntity::from($requestData);

            if ($this->contentArticleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentArticle/show', summary: '获取文章详情接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentArticle = $this->contentArticleService->getOneById($id);
            if (empty($contentArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentArticleResponse::from($contentArticle);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentArticle/update', summary: '更新文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleResponse::class),
        ],
    ))]
    public function update(ContentArticleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentArticle = $this->contentArticleService->getOneById($id);
            if (empty($contentArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentArticleEntity::from($requestData);

            $this->contentArticleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentArticle/destroy', summary: '删除文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentArticleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentArticleService->removeByIds($requestData['ids'])) {
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
