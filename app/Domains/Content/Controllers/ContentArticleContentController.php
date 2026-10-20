<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentArticleContentEntity;
use App\Domains\Content\Requests\ContentArticleContent\ContentArticleContentCreateRequest;
use App\Domains\Content\Requests\ContentArticleContent\ContentArticleContentDestroyRequest;
use App\Domains\Content\Requests\ContentArticleContent\ContentArticleContentQueryRequest;
use App\Domains\Content\Requests\ContentArticleContent\ContentArticleContentUpdateRequest;
use App\Domains\Content\Responses\ContentArticleContent\ContentArticleContentDestroyResponse;
use App\Domains\Content\Responses\ContentArticleContent\ContentArticleContentQueryResponse;
use App\Domains\Content\Responses\ContentArticleContent\ContentArticleContentResponse;
use App\Domains\Content\Services\ContentArticleContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentArticleContentController extends BaseController
{
    public function __construct(
        private readonly ContentArticleContentService $contentArticleContentService,
    ) {}

    #[OA\Post(path: '/contentArticleContent/search', summary: '查询文章正文列表接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleContentQueryResponse::class),
        ],
    ))]
    public function search(ContentArticleContentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentArticleContentQueryRequest::getArticleId])) {
                $condition[] = [ContentArticleContentEntity::getArticleId, '=', $requestData[ContentArticleContentQueryRequest::getArticleId]];
            }

            $result = $this->contentArticleContentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentArticleContentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentArticleContentQueryResponse::from($result);
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

    #[OA\Post(path: '/contentArticleContent/store', summary: '新增文章正文接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleContentResponse::class),
        ],
    ))]
    public function store(ContentArticleContentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentArticleContentEntity::from($requestData);

            if ($this->contentArticleContentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentArticleContent/show', summary: '获取文章正文详情接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleContentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentArticleContent = $this->contentArticleContentService->getOneById($id);
            if (empty($contentArticleContent)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentArticleContentResponse::from($contentArticleContent);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentArticleContent/update', summary: '更新文章正文接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleContentResponse::class),
        ],
    ))]
    public function update(ContentArticleContentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentArticleContent = $this->contentArticleContentService->getOneById($id);
            if (empty($contentArticleContent)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentArticleContentEntity::from($requestData);

            $this->contentArticleContentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentArticleContent/destroy', summary: '删除文章正文接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentArticleContentDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentArticleContentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentArticleContentService->removeByIds($requestData['ids'])) {
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
