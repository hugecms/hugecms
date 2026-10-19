<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentTagEntity;
use App\Domains\Content\Services\ContentTagService;
use App\Domains\Content\Requests\ContentTag\ContentTagCreateRequest;
use App\Domains\Content\Requests\ContentTag\ContentTagDestroyRequest;
use App\Domains\Content\Requests\ContentTag\ContentTagQueryRequest;
use App\Domains\Content\Requests\ContentTag\ContentTagUpdateRequest;
use App\Domains\Content\Responses\ContentTag\ContentTagDestroyResponse;
use App\Domains\Content\Responses\ContentTag\ContentTagQueryResponse;
use App\Domains\Content\Responses\ContentTag\ContentTagResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentTagController extends BaseController
{
    public function __construct(
        private readonly ContentTagService $contentTagService,
    ) {}

    #[OA\Post(path: '/contentTag/search', summary: '查询标签列表接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTagQueryResponse::class),
        ],
    ))]
    public function search(ContentTagQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentTagQueryRequest::getId])) {
                $condition[] = [ContentTagEntity::getId, '=', $requestData[ContentTagQueryRequest::getId]];
            }
            if (isset($requestData[ContentTagQueryRequest::getName])) {
                $condition[] = [ContentTagEntity::getName, '=', $requestData[ContentTagQueryRequest::getName]];
            }
            
            $result = $this->contentTagService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentTagResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentTagQueryResponse::from($result);
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

    #[OA\Post(path: '/contentTag/store', summary: '新增标签接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTagResponse::class),
        ],
    ))]
    public function store(ContentTagCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentTagEntity::from($requestData);

            if ($this->contentTagService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentTag/show', summary: '获取标签详情接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTagResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentTag = $this->contentTagService->getOneById($id);
            if (empty($contentTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentTagResponse::from($contentTag);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentTag/update', summary: '更新标签接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTagResponse::class),
        ],
    ))]
    public function update(ContentTagUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentTag = $this->contentTagService->getOneById($id);
            if (empty($contentTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentTagEntity::from($requestData);

            $this->contentTagService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentTag/destroy', summary: '删除标签接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTagDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentTagDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentTagService->removeByIds($requestData['ids'])) {
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
