<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\TaxonomyEntity;
use App\Services\TaxonomyService;
use App\Api\Admin\Requests\Taxonomy\TaxonomyCreateRequest;
use App\Api\Admin\Requests\Taxonomy\TaxonomyDestroyRequest;
use App\Api\Admin\Requests\Taxonomy\TaxonomyQueryRequest;
use App\Api\Admin\Requests\Taxonomy\TaxonomyUpdateRequest;
use App\Api\Admin\Responses\Taxonomy\TaxonomyDestroyResponse;
use App\Api\Admin\Responses\Taxonomy\TaxonomyQueryResponse;
use App\Api\Admin\Responses\Taxonomy\TaxonomyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class TaxonomyController extends BaseController
{
    public function __construct(
        private readonly TaxonomyService $taxonomyService,
    ) {}

    #[OA\Post(path: '/taxonomy/search', summary: '查询分类法列表接口', security: [['bearerAuth' => []]], tags: ['分类法模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TaxonomyQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TaxonomyQueryResponse::class),
        ],
    ))]
    public function search(TaxonomyQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[TaxonomyQueryRequest::getId])) {
                $condition[] = [TaxonomyEntity::getId, '=', $requestData[TaxonomyQueryRequest::getId]];
            }
            if (isset($requestData[TaxonomyQueryRequest::getAlias])) {
                $condition[] = [TaxonomyEntity::getAlias, '=', $requestData[TaxonomyQueryRequest::getAlias]];
            }
            if (isset($requestData[TaxonomyQueryRequest::getModelId])) {
                $condition[] = [TaxonomyEntity::getModelId, '=', $requestData[TaxonomyQueryRequest::getModelId]];
            }
            
            $result = $this->taxonomyService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = TaxonomyResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = TaxonomyQueryResponse::from($result);
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

    #[OA\Post(path: '/taxonomy/store', summary: '新增分类法接口', security: [['bearerAuth' => []]], tags: ['分类法模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TaxonomyCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TaxonomyResponse::class),
        ],
    ))]
    public function store(TaxonomyCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = TaxonomyEntity::from($requestData);

            if ($this->taxonomyService->save($input->toEntity())) {
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

    #[OA\Get(path: '/taxonomy/show', summary: '获取分类法详情接口', security: [['bearerAuth' => []]], tags: ['分类法模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TaxonomyResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $taxonomy = $this->taxonomyService->getOneById($id);
            if (empty($taxonomy)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = TaxonomyResponse::from($taxonomy);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/taxonomy/update', summary: '更新分类法接口', security: [['bearerAuth' => []]], tags: ['分类法模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TaxonomyUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TaxonomyResponse::class),
        ],
    ))]
    public function update(TaxonomyUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $taxonomy = $this->taxonomyService->getOneById($id);
            if (empty($taxonomy)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = TaxonomyEntity::from($requestData);

            $this->taxonomyService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/taxonomy/destroy', summary: '删除分类法接口', security: [['bearerAuth' => []]], tags: ['分类法模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TaxonomyDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TaxonomyDestroyResponse::class),
        ],
    ))]
    public function destroy(TaxonomyDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->taxonomyService->removeByIds($requestData['ids'])) {
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
