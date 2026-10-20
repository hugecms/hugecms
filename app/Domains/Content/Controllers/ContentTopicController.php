<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentTopicEntity;
use App\Domains\Content\Requests\ContentTopic\ContentTopicCreateRequest;
use App\Domains\Content\Requests\ContentTopic\ContentTopicDestroyRequest;
use App\Domains\Content\Requests\ContentTopic\ContentTopicQueryRequest;
use App\Domains\Content\Requests\ContentTopic\ContentTopicUpdateRequest;
use App\Domains\Content\Responses\ContentTopic\ContentTopicDestroyResponse;
use App\Domains\Content\Responses\ContentTopic\ContentTopicQueryResponse;
use App\Domains\Content\Responses\ContentTopic\ContentTopicResponse;
use App\Domains\Content\Services\ContentTopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentTopicController extends BaseController
{
    public function __construct(
        private readonly ContentTopicService $contentTopicService,
    ) {}

    #[OA\Post(path: '/contentTopic/search', summary: '查询专题列表接口', security: [['bearerAuth' => []]], tags: ['专题模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicQueryResponse::class),
        ],
    ))]
    public function search(ContentTopicQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentTopicQueryRequest::getId])) {
                $condition[] = [ContentTopicEntity::getId, '=', $requestData[ContentTopicQueryRequest::getId]];
            }
            if (isset($requestData[ContentTopicQueryRequest::getSlug])) {
                $condition[] = [ContentTopicEntity::getSlug, '=', $requestData[ContentTopicQueryRequest::getSlug]];
            }

            $result = $this->contentTopicService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentTopicResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentTopicQueryResponse::from($result);
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

    #[OA\Post(path: '/contentTopic/store', summary: '新增专题接口', security: [['bearerAuth' => []]], tags: ['专题模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicResponse::class),
        ],
    ))]
    public function store(ContentTopicCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentTopicEntity::from($requestData);

            if ($this->contentTopicService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentTopic/show', summary: '获取专题详情接口', security: [['bearerAuth' => []]], tags: ['专题模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentTopic = $this->contentTopicService->getOneById($id);
            if (empty($contentTopic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentTopicResponse::from($contentTopic);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentTopic/update', summary: '更新专题接口', security: [['bearerAuth' => []]], tags: ['专题模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicResponse::class),
        ],
    ))]
    public function update(ContentTopicUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentTopic = $this->contentTopicService->getOneById($id);
            if (empty($contentTopic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentTopicEntity::from($requestData);

            $this->contentTopicService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentTopic/destroy', summary: '删除专题接口', security: [['bearerAuth' => []]], tags: ['专题模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTopicDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentTopicDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentTopicDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentTopicService->removeByIds($requestData['ids'])) {
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
