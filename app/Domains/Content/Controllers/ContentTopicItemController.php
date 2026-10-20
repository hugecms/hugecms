<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentTopicItemEntity;
use App\Domains\Content\Requests\ContentTopicItem\ContentTopicItemCreateRequest;
use App\Domains\Content\Requests\ContentTopicItem\ContentTopicItemDestroyRequest;
use App\Domains\Content\Requests\ContentTopicItem\ContentTopicItemQueryRequest;
use App\Domains\Content\Requests\ContentTopicItem\ContentTopicItemUpdateRequest;
use App\Domains\Content\Responses\ContentTopicItem\ContentTopicItemDestroyResponse;
use App\Domains\Content\Responses\ContentTopicItem\ContentTopicItemQueryResponse;
use App\Domains\Content\Responses\ContentTopicItem\ContentTopicItemResponse;
use App\Domains\Content\Services\ContentTopicItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentTopicItemController extends BaseController
{
    public function __construct(
        private readonly ContentTopicItemService $contentTopicItemService,
    ) {}

    #[OA\Post(path: '/contentTopicItem/search', summary: '查询专题内容关联列表接口', security: [['bearerAuth' => []]], tags: ['专题内容关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicItemQueryResponse::class),
        ],
    ))]
    public function search(ContentTopicItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentTopicItemQueryRequest::getTargetType])) {
                $condition[] = [ContentTopicItemEntity::getTargetType, '=', $requestData[ContentTopicItemQueryRequest::getTargetType]];
            }
            if (isset($requestData[ContentTopicItemQueryRequest::getTargetId])) {
                $condition[] = [ContentTopicItemEntity::getTargetId, '=', $requestData[ContentTopicItemQueryRequest::getTargetId]];
            }
            if (isset($requestData[ContentTopicItemQueryRequest::getId])) {
                $condition[] = [ContentTopicItemEntity::getId, '=', $requestData[ContentTopicItemQueryRequest::getId]];
            }
            if (isset($requestData[ContentTopicItemQueryRequest::getTopicId])) {
                $condition[] = [ContentTopicItemEntity::getTopicId, '=', $requestData[ContentTopicItemQueryRequest::getTopicId]];
            }
            if (isset($requestData[ContentTopicItemQueryRequest::getTargetType])) {
                $condition[] = [ContentTopicItemEntity::getTargetType, '=', $requestData[ContentTopicItemQueryRequest::getTargetType]];
            }
            if (isset($requestData[ContentTopicItemQueryRequest::getTargetId])) {
                $condition[] = [ContentTopicItemEntity::getTargetId, '=', $requestData[ContentTopicItemQueryRequest::getTargetId]];
            }

            $result = $this->contentTopicItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentTopicItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentTopicItemQueryResponse::from($result);
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

    #[OA\Post(path: '/contentTopicItem/store', summary: '新增专题内容关联接口', security: [['bearerAuth' => []]], tags: ['专题内容关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicItemResponse::class),
        ],
    ))]
    public function store(ContentTopicItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentTopicItemEntity::from($requestData);

            if ($this->contentTopicItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentTopicItem/show', summary: '获取专题内容关联详情接口', security: [['bearerAuth' => []]], tags: ['专题内容关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentTopicItem = $this->contentTopicItemService->getOneById($id);
            if (empty($contentTopicItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentTopicItemResponse::from($contentTopicItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentTopicItem/update', summary: '更新专题内容关联接口', security: [['bearerAuth' => []]], tags: ['专题内容关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicItemResponse::class),
        ],
    ))]
    public function update(ContentTopicItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentTopicItem = $this->contentTopicItemService->getOneById($id);
            if (empty($contentTopicItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentTopicItemEntity::from($requestData);

            $this->contentTopicItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentTopicItem/destroy', summary: '删除专题内容关联接口', security: [['bearerAuth' => []]], tags: ['专题内容关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicItemDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentTopicItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentTopicItemService->removeByIds($requestData['ids'])) {
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
