<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\ModelFieldEntity;
use App\Services\ModelFieldService;
use App\Api\Admin\Requests\ModelField\ModelFieldCreateRequest;
use App\Api\Admin\Requests\ModelField\ModelFieldDestroyRequest;
use App\Api\Admin\Requests\ModelField\ModelFieldQueryRequest;
use App\Api\Admin\Requests\ModelField\ModelFieldUpdateRequest;
use App\Api\Admin\Responses\ModelField\ModelFieldDestroyResponse;
use App\Api\Admin\Responses\ModelField\ModelFieldQueryResponse;
use App\Api\Admin\Responses\ModelField\ModelFieldResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ModelFieldController extends BaseController
{
    public function __construct(
        private readonly ModelFieldService $modelFieldService,
    ) {}

    #[OA\Post(path: '/modelField/search', summary: '查询模型字段列表接口', security: [['bearerAuth' => []]], tags: ['模型字段模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ModelFieldQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ModelFieldQueryResponse::class),
        ],
    ))]
    public function search(ModelFieldQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ModelFieldQueryRequest::getColumnName])) {
                $condition[] = [ModelFieldEntity::getColumnName, '=', $requestData[ModelFieldQueryRequest::getColumnName]];
            }
            if (isset($requestData[ModelFieldQueryRequest::getFieldName])) {
                $condition[] = [ModelFieldEntity::getFieldName, '=', $requestData[ModelFieldQueryRequest::getFieldName]];
            }
            if (isset($requestData[ModelFieldQueryRequest::getId])) {
                $condition[] = [ModelFieldEntity::getId, '=', $requestData[ModelFieldQueryRequest::getId]];
            }
            
            $result = $this->modelFieldService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ModelFieldResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ModelFieldQueryResponse::from($result);
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

    #[OA\Post(path: '/modelField/store', summary: '新增模型字段接口', security: [['bearerAuth' => []]], tags: ['模型字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ModelFieldCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ModelFieldResponse::class),
        ],
    ))]
    public function store(ModelFieldCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ModelFieldEntity::from($requestData);

            if ($this->modelFieldService->save($input->toEntity())) {
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

    #[OA\Get(path: '/modelField/show', summary: '获取模型字段详情接口', security: [['bearerAuth' => []]], tags: ['模型字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ModelFieldResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $modelField = $this->modelFieldService->getOneById($id);
            if (empty($modelField)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ModelFieldResponse::from($modelField);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/modelField/update', summary: '更新模型字段接口', security: [['bearerAuth' => []]], tags: ['模型字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ModelFieldUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ModelFieldResponse::class),
        ],
    ))]
    public function update(ModelFieldUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $modelField = $this->modelFieldService->getOneById($id);
            if (empty($modelField)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ModelFieldEntity::from($requestData);

            $this->modelFieldService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/modelField/destroy', summary: '删除模型字段接口', security: [['bearerAuth' => []]], tags: ['模型字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ModelFieldDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ModelFieldDestroyResponse::class),
        ],
    ))]
    public function destroy(ModelFieldDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->modelFieldService->removeByIds($requestData['ids'])) {
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
