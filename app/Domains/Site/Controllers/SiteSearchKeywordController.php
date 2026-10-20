<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteSearchKeywordEntity;
use App\Domains\Site\Requests\SiteSearchKeyword\SiteSearchKeywordCreateRequest;
use App\Domains\Site\Requests\SiteSearchKeyword\SiteSearchKeywordDestroyRequest;
use App\Domains\Site\Requests\SiteSearchKeyword\SiteSearchKeywordQueryRequest;
use App\Domains\Site\Requests\SiteSearchKeyword\SiteSearchKeywordUpdateRequest;
use App\Domains\Site\Responses\SiteSearchKeyword\SiteSearchKeywordDestroyResponse;
use App\Domains\Site\Responses\SiteSearchKeyword\SiteSearchKeywordQueryResponse;
use App\Domains\Site\Responses\SiteSearchKeyword\SiteSearchKeywordResponse;
use App\Domains\Site\Services\SiteSearchKeywordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteSearchKeywordController extends BaseController
{
    public function __construct(
        private readonly SiteSearchKeywordService $siteSearchKeywordService,
    ) {}

    #[OA\Post(path: '/siteSearchKeyword/search', summary: '查询搜索热词列表接口', security: [['bearerAuth' => []]], tags: ['搜索热词模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteSearchKeywordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteSearchKeywordQueryResponse::class),
        ],
    ))]
    public function search(SiteSearchKeywordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteSearchKeywordQueryRequest::getSearchCount])) {
                $condition[] = [SiteSearchKeywordEntity::getSearchCount, '=', $requestData[SiteSearchKeywordQueryRequest::getSearchCount]];
            }
            if (isset($requestData[SiteSearchKeywordQueryRequest::getId])) {
                $condition[] = [SiteSearchKeywordEntity::getId, '=', $requestData[SiteSearchKeywordQueryRequest::getId]];
            }
            if (isset($requestData[SiteSearchKeywordQueryRequest::getKeyword])) {
                $condition[] = [SiteSearchKeywordEntity::getKeyword, '=', $requestData[SiteSearchKeywordQueryRequest::getKeyword]];
            }

            $result = $this->siteSearchKeywordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteSearchKeywordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteSearchKeywordQueryResponse::from($result);
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

    #[OA\Post(path: '/siteSearchKeyword/store', summary: '新增搜索热词接口', security: [['bearerAuth' => []]], tags: ['搜索热词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteSearchKeywordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteSearchKeywordResponse::class),
        ],
    ))]
    public function store(SiteSearchKeywordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteSearchKeywordEntity::from($requestData);

            if ($this->siteSearchKeywordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteSearchKeyword/show', summary: '获取搜索热词详情接口', security: [['bearerAuth' => []]], tags: ['搜索热词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteSearchKeywordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteSearchKeyword = $this->siteSearchKeywordService->getOneById($id);
            if (empty($siteSearchKeyword)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteSearchKeywordResponse::from($siteSearchKeyword);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteSearchKeyword/update', summary: '更新搜索热词接口', security: [['bearerAuth' => []]], tags: ['搜索热词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteSearchKeywordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteSearchKeywordResponse::class),
        ],
    ))]
    public function update(SiteSearchKeywordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteSearchKeyword = $this->siteSearchKeywordService->getOneById($id);
            if (empty($siteSearchKeyword)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteSearchKeywordEntity::from($requestData);

            $this->siteSearchKeywordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteSearchKeyword/destroy', summary: '删除搜索热词接口', security: [['bearerAuth' => []]], tags: ['搜索热词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteSearchKeywordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteSearchKeywordDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteSearchKeywordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteSearchKeywordService->removeByIds($requestData['ids'])) {
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
