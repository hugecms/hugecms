<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\DataArticleEntity;
use App\Services\DataArticleService;
use App\Api\Admin\Requests\DataArticle\DataArticleCreateRequest;
use App\Api\Admin\Requests\DataArticle\DataArticleDestroyRequest;
use App\Api\Admin\Requests\DataArticle\DataArticleQueryRequest;
use App\Api\Admin\Requests\DataArticle\DataArticleUpdateRequest;
use App\Api\Admin\Responses\DataArticle\DataArticleDestroyResponse;
use App\Api\Admin\Responses\DataArticle\DataArticleQueryResponse;
use App\Api\Admin\Responses\DataArticle\DataArticleResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class DataArticleController extends BaseController
{
    public function __construct(
        private readonly DataArticleService $dataArticleService,
    ) {}

    #[OA\Post(path: '/dataArticle/search', summary: '查询文章模型数据列表接口', security: [['bearerAuth' => []]], tags: ['文章模型数据模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DataArticleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: DataArticleQueryResponse::class),
        ],
    ))]
    public function search(DataArticleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[DataArticleQueryRequest::getContentId])) {
                $condition[] = [DataArticleEntity::getContentId, '=', $requestData[DataArticleQueryRequest::getContentId]];
            }
            if (isset($requestData[DataArticleQueryRequest::getId])) {
                $condition[] = [DataArticleEntity::getId, '=', $requestData[DataArticleQueryRequest::getId]];
            }
            
            $result = $this->dataArticleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = DataArticleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = DataArticleQueryResponse::from($result);
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

    #[OA\Post(path: '/dataArticle/store', summary: '新增文章模型数据接口', security: [['bearerAuth' => []]], tags: ['文章模型数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DataArticleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: DataArticleResponse::class),
        ],
    ))]
    public function store(DataArticleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = DataArticleEntity::from($requestData);

            if ($this->dataArticleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/dataArticle/show', summary: '获取文章模型数据详情接口', security: [['bearerAuth' => []]], tags: ['文章模型数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: DataArticleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $dataArticle = $this->dataArticleService->getOneById($id);
            if (empty($dataArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = DataArticleResponse::from($dataArticle);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/dataArticle/update', summary: '更新文章模型数据接口', security: [['bearerAuth' => []]], tags: ['文章模型数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DataArticleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: DataArticleResponse::class),
        ],
    ))]
    public function update(DataArticleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $dataArticle = $this->dataArticleService->getOneById($id);
            if (empty($dataArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = DataArticleEntity::from($requestData);

            $this->dataArticleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/dataArticle/destroy', summary: '删除文章模型数据接口', security: [['bearerAuth' => []]], tags: ['文章模型数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DataArticleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: DataArticleDestroyResponse::class),
        ],
    ))]
    public function destroy(DataArticleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->dataArticleService->removeByIds($requestData['ids'])) {
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
