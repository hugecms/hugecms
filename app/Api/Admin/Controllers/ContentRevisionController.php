<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\ContentRevision\ContentRevisionCreateRequest;
use App\Api\Admin\Requests\ContentRevision\ContentRevisionDestroyRequest;
use App\Api\Admin\Requests\ContentRevision\ContentRevisionQueryRequest;
use App\Api\Admin\Requests\ContentRevision\ContentRevisionUpdateRequest;
use App\Api\Admin\Responses\ContentRevision\ContentRevisionDestroyResponse;
use App\Api\Admin\Responses\ContentRevision\ContentRevisionQueryResponse;
use App\Api\Admin\Responses\ContentRevision\ContentRevisionResponse;
use App\Entities\ContentRevisionEntity;
use App\Services\ContentRevisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentRevisionController extends BaseController
{
    public function __construct(
        private readonly ContentRevisionService $contentRevisionService,
    ) {}

    #[OA\Post(path: '/contentRevision/search', summary: '查询内容版本列表接口', security: [['bearerAuth' => []]], tags: ['内容版本模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentRevisionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentRevisionQueryResponse::class),
        ],
    ))]
    public function search(ContentRevisionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[ContentRevisionQueryRequest::getContentId])) {
                $condition[] = [ContentRevisionEntity::getContentId, '=', $requestData[ContentRevisionQueryRequest::getContentId]];
            }
            if (isset($requestData[ContentRevisionQueryRequest::getId])) {
                $condition[] = [ContentRevisionEntity::getId, '=', $requestData[ContentRevisionQueryRequest::getId]];
            }

            $result = $this->contentRevisionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentRevisionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentRevisionQueryResponse::from($result);
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

    #[OA\Post(path: '/contentRevision/store', summary: '新增内容版本接口', security: [['bearerAuth' => []]], tags: ['内容版本模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentRevisionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentRevisionResponse::class),
        ],
    ))]
    public function store(ContentRevisionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = ContentRevisionEntity::from($requestData);

            if ($this->contentRevisionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentRevision/show', summary: '获取内容版本详情接口', security: [['bearerAuth' => []]], tags: ['内容版本模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentRevisionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentRevision = $this->contentRevisionService->getOneById($id);
            if (empty($contentRevision)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentRevisionResponse::from($contentRevision);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentRevision/update', summary: '更新内容版本接口', security: [['bearerAuth' => []]], tags: ['内容版本模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentRevisionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentRevisionResponse::class),
        ],
    ))]
    public function update(ContentRevisionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $contentRevision = $this->contentRevisionService->getOneById($id);
            if (empty($contentRevision)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentRevisionEntity::from($requestData);

            $this->contentRevisionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentRevision/destroy', summary: '删除内容版本接口', security: [['bearerAuth' => []]], tags: ['内容版本模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentRevisionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentRevisionDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentRevisionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->contentRevisionService->removeByIds($requestData['ids'])) {
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
