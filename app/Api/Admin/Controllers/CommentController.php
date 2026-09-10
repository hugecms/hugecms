<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\CommentEntity;
use App\Services\CommentService;
use App\Api\Admin\Requests\Comment\CommentCreateRequest;
use App\Api\Admin\Requests\Comment\CommentDestroyRequest;
use App\Api\Admin\Requests\Comment\CommentQueryRequest;
use App\Api\Admin\Requests\Comment\CommentUpdateRequest;
use App\Api\Admin\Responses\Comment\CommentDestroyResponse;
use App\Api\Admin\Responses\Comment\CommentQueryResponse;
use App\Api\Admin\Responses\Comment\CommentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class CommentController extends BaseController
{
    public function __construct(
        private readonly CommentService $commentService,
    ) {}

    #[OA\Post(path: '/comment/search', summary: '查询评论列表接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CommentQueryResponse::class),
        ],
    ))]
    public function search(CommentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[CommentQueryRequest::getCreatedAt])) {
                $condition[] = [CommentEntity::getCreatedAt, '=', $requestData[CommentQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[CommentQueryRequest::getParentId])) {
                $condition[] = [CommentEntity::getParentId, '=', $requestData[CommentQueryRequest::getParentId]];
            }
            if (isset($requestData[CommentQueryRequest::getCreatedAt])) {
                $condition[] = [CommentEntity::getCreatedAt, '=', $requestData[CommentQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[CommentQueryRequest::getUserId])) {
                $condition[] = [CommentEntity::getUserId, '=', $requestData[CommentQueryRequest::getUserId]];
            }
            if (isset($requestData[CommentQueryRequest::getId])) {
                $condition[] = [CommentEntity::getId, '=', $requestData[CommentQueryRequest::getId]];
            }
            
            $result = $this->commentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = CommentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = CommentQueryResponse::from($result);
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

    #[OA\Post(path: '/comment/store', summary: '新增评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CommentResponse::class),
        ],
    ))]
    public function store(CommentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = CommentEntity::from($requestData);

            if ($this->commentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/comment/show', summary: '获取评论详情接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CommentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $comment = $this->commentService->getOneById($id);
            if (empty($comment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = CommentResponse::from($comment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/comment/update', summary: '更新评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CommentResponse::class),
        ],
    ))]
    public function update(CommentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $comment = $this->commentService->getOneById($id);
            if (empty($comment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = CommentEntity::from($requestData);

            $this->commentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/comment/destroy', summary: '删除评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CommentDestroyResponse::class),
        ],
    ))]
    public function destroy(CommentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->commentService->removeByIds($requestData['ids'])) {
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
