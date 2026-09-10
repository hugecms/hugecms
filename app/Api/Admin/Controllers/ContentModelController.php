<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\ContentModelEntity;
use App\Services\ContentModelService;
use App\Api\Admin\Requests\ContentModel\ContentModelCreateRequest;
use App\Api\Admin\Requests\ContentModel\ContentModelDestroyRequest;
use App\Api\Admin\Requests\ContentModel\ContentModelQueryRequest;
use App\Api\Admin\Requests\ContentModel\ContentModelUpdateRequest;
use App\Api\Admin\Responses\ContentModel\ContentModelDestroyResponse;
use App\Api\Admin\Responses\ContentModel\ContentModelQueryResponse;
use App\Api\Admin\Responses\ContentModel\ContentModelResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentModelController extends BaseController
{
    public function __construct(
        private readonly ContentModelService $contentModelService,
    ) {}

    #[OA\Post(path: '/contentModel/search', summary: '查询内容模型列表接口', security: [['bearerAuth' => []]], tags: ['内容模型模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentModelQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentModelQueryResponse::class),
        ],
    ))]
    public function search(ContentModelQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentModelQueryRequest::getAlias])) {
                $condition[] = [ContentModelEntity::getAlias, '=', $requestData[ContentModelQueryRequest::getAlias]];
            }
            if (isset($requestData[ContentModelQueryRequest::getTableName])) {
                $condition[] = [ContentModelEntity::getTableName, '=', $requestData[ContentModelQueryRequest::getTableName]];
            }
            if (isset($requestData[ContentModelQueryRequest::getId])) {
                $condition[] = [ContentModelEntity::getId, '=', $requestData[ContentModelQueryRequest::getId]];
            }
            
            $result = $this->contentModelService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentModelResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentModelQueryResponse::from($result);
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

    #[OA\Post(path: '/contentModel/store', summary: '新增内容模型接口', security: [['bearerAuth' => []]], tags: ['内容模型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentModelCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentModelResponse::class),
        ],
    ))]
    public function store(ContentModelCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentModelEntity::from($requestData);

            if ($this->contentModelService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentModel/show', summary: '获取内容模型详情接口', security: [['bearerAuth' => []]], tags: ['内容模型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentModelResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentModel = $this->contentModelService->getOneById($id);
            if (empty($contentModel)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentModelResponse::from($contentModel);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentModel/update', summary: '更新内容模型接口', security: [['bearerAuth' => []]], tags: ['内容模型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentModelUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentModelResponse::class),
        ],
    ))]
    public function update(ContentModelUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentModel = $this->contentModelService->getOneById($id);
            if (empty($contentModel)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentModelEntity::from($requestData);

            $this->contentModelService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentModel/destroy', summary: '删除内容模型接口', security: [['bearerAuth' => []]], tags: ['内容模型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentModelDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentModelDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentModelDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentModelService->removeByIds($requestData['ids'])) {
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
