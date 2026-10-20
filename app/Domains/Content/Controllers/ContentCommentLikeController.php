<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentCommentLikeEntity;
use App\Domains\Content\Requests\ContentCommentLike\ContentCommentLikeCreateRequest;
use App\Domains\Content\Requests\ContentCommentLike\ContentCommentLikeDestroyRequest;
use App\Domains\Content\Requests\ContentCommentLike\ContentCommentLikeQueryRequest;
use App\Domains\Content\Requests\ContentCommentLike\ContentCommentLikeUpdateRequest;
use App\Domains\Content\Responses\ContentCommentLike\ContentCommentLikeDestroyResponse;
use App\Domains\Content\Responses\ContentCommentLike\ContentCommentLikeQueryResponse;
use App\Domains\Content\Responses\ContentCommentLike\ContentCommentLikeResponse;
use App\Domains\Content\Services\ContentCommentLikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentCommentLikeController extends BaseController
{
    public function __construct(
        private readonly ContentCommentLikeService $contentCommentLikeService,
    ) {}

    #[OA\Post(path: '/contentCommentLike/search', summary: '查询评论点赞列表接口', security: [['bearerAuth' => []]], tags: ['评论点赞模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentLikeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentLikeQueryResponse::class),
        ],
    ))]
    public function search(ContentCommentLikeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentCommentLikeQueryRequest::getUserId])) {
                $condition[] = [ContentCommentLikeEntity::getUserId, '=', $requestData[ContentCommentLikeQueryRequest::getUserId]];
            }
            if (isset($requestData[ContentCommentLikeQueryRequest::getCommentId])) {
                $condition[] = [ContentCommentLikeEntity::getCommentId, '=', $requestData[ContentCommentLikeQueryRequest::getCommentId]];
            }
            if (isset($requestData[ContentCommentLikeQueryRequest::getUserId])) {
                $condition[] = [ContentCommentLikeEntity::getUserId, '=', $requestData[ContentCommentLikeQueryRequest::getUserId]];
            }

            $result = $this->contentCommentLikeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentCommentLikeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentCommentLikeQueryResponse::from($result);
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

    #[OA\Post(path: '/contentCommentLike/store', summary: '新增评论点赞接口', security: [['bearerAuth' => []]], tags: ['评论点赞模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentLikeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentLikeResponse::class),
        ],
    ))]
    public function store(ContentCommentLikeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentCommentLikeEntity::from($requestData);

            if ($this->contentCommentLikeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentCommentLike/show', summary: '获取评论点赞详情接口', security: [['bearerAuth' => []]], tags: ['评论点赞模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentLikeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentCommentLike = $this->contentCommentLikeService->getOneById($id);
            if (empty($contentCommentLike)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentCommentLikeResponse::from($contentCommentLike);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentCommentLike/update', summary: '更新评论点赞接口', security: [['bearerAuth' => []]], tags: ['评论点赞模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentLikeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentLikeResponse::class),
        ],
    ))]
    public function update(ContentCommentLikeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentCommentLike = $this->contentCommentLikeService->getOneById($id);
            if (empty($contentCommentLike)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentCommentLikeEntity::from($requestData);

            $this->contentCommentLikeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentCommentLike/destroy', summary: '删除评论点赞接口', security: [['bearerAuth' => []]], tags: ['评论点赞模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentLikeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentLikeDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentCommentLikeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentCommentLikeService->removeByIds($requestData['ids'])) {
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
