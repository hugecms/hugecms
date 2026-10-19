<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentCategoryEntity;
use App\Domains\Content\Services\ContentCategoryService;
use App\Domains\Content\Requests\ContentCategory\ContentCategoryCreateRequest;
use App\Domains\Content\Requests\ContentCategory\ContentCategoryDestroyRequest;
use App\Domains\Content\Requests\ContentCategory\ContentCategoryQueryRequest;
use App\Domains\Content\Requests\ContentCategory\ContentCategoryUpdateRequest;
use App\Domains\Content\Responses\ContentCategory\ContentCategoryDestroyResponse;
use App\Domains\Content\Responses\ContentCategory\ContentCategoryQueryResponse;
use App\Domains\Content\Responses\ContentCategory\ContentCategoryResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentCategoryController extends BaseController
{
    public function __construct(
        private readonly ContentCategoryService $contentCategoryService,
    ) {}

    #[OA\Post(path: '/contentCategory/search', summary: '查询栏目列表接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCategoryQueryResponse::class),
        ],
    ))]
    public function search(ContentCategoryQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentCategoryQueryRequest::getParentId])) {
                $condition[] = [ContentCategoryEntity::getParentId, '=', $requestData[ContentCategoryQueryRequest::getParentId]];
            }
            if (isset($requestData[ContentCategoryQueryRequest::getId])) {
                $condition[] = [ContentCategoryEntity::getId, '=', $requestData[ContentCategoryQueryRequest::getId]];
            }
            if (isset($requestData[ContentCategoryQueryRequest::getSlug])) {
                $condition[] = [ContentCategoryEntity::getSlug, '=', $requestData[ContentCategoryQueryRequest::getSlug]];
            }
            
            $result = $this->contentCategoryService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentCategoryResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentCategoryQueryResponse::from($result);
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

    #[OA\Post(path: '/contentCategory/store', summary: '新增栏目接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCategoryResponse::class),
        ],
    ))]
    public function store(ContentCategoryCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentCategoryEntity::from($requestData);

            if ($this->contentCategoryService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentCategory/show', summary: '获取栏目详情接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCategoryResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentCategory = $this->contentCategoryService->getOneById($id);
            if (empty($contentCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentCategoryResponse::from($contentCategory);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentCategory/update', summary: '更新栏目接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCategoryResponse::class),
        ],
    ))]
    public function update(ContentCategoryUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentCategory = $this->contentCategoryService->getOneById($id);
            if (empty($contentCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentCategoryEntity::from($requestData);

            $this->contentCategoryService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentCategory/destroy', summary: '删除栏目接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentCategoryDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentCategoryDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentCategoryService->removeByIds($requestData['ids'])) {
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
