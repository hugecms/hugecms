<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentCommentEntity;
use App\Domains\Content\Requests\ContentComment\ContentCommentCreateRequest;
use App\Domains\Content\Requests\ContentComment\ContentCommentDestroyRequest;
use App\Domains\Content\Requests\ContentComment\ContentCommentQueryRequest;
use App\Domains\Content\Requests\ContentComment\ContentCommentUpdateRequest;
use App\Domains\Content\Responses\ContentComment\ContentCommentDestroyResponse;
use App\Domains\Content\Responses\ContentComment\ContentCommentQueryResponse;
use App\Domains\Content\Responses\ContentComment\ContentCommentResponse;
use App\Domains\Content\Services\ContentCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentCommentController extends BaseController
{
    public function __construct(
        private readonly ContentCommentService $contentCommentService,
    ) {}

    #[OA\Post(path: '/contentComment/search', summary: '查询评论列表接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentQueryResponse::class),
        ],
    ))]
    public function search(ContentCommentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentCommentQueryRequest::getTargetType])) {
                $condition[] = [ContentCommentEntity::getTargetType, '=', $requestData[ContentCommentQueryRequest::getTargetType]];
            }
            if (isset($requestData[ContentCommentQueryRequest::getTargetId])) {
                $condition[] = [ContentCommentEntity::getTargetId, '=', $requestData[ContentCommentQueryRequest::getTargetId]];
            }
            if (isset($requestData[ContentCommentQueryRequest::getUserId])) {
                $condition[] = [ContentCommentEntity::getUserId, '=', $requestData[ContentCommentQueryRequest::getUserId]];
            }
            if (isset($requestData[ContentCommentQueryRequest::getId])) {
                $condition[] = [ContentCommentEntity::getId, '=', $requestData[ContentCommentQueryRequest::getId]];
            }

            $result = $this->contentCommentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentCommentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentCommentQueryResponse::from($result);
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

    #[OA\Post(path: '/contentComment/store', summary: '新增评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentResponse::class),
        ],
    ))]
    public function store(ContentCommentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentCommentEntity::from($requestData);

            if ($this->contentCommentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentComment/show', summary: '获取评论详情接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentComment = $this->contentCommentService->getOneById($id);
            if (empty($contentComment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentCommentResponse::from($contentComment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentComment/update', summary: '更新评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentResponse::class),
        ],
    ))]
    public function update(ContentCommentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentComment = $this->contentCommentService->getOneById($id);
            if (empty($contentComment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentCommentEntity::from($requestData);

            $this->contentCommentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentComment/destroy', summary: '删除评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCommentDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentCommentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentCommentService->removeByIds($requestData['ids'])) {
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
