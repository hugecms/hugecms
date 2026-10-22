<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteAdEntity;
use App\Domains\Site\Requests\SiteAd\SiteAdCreateRequest;
use App\Domains\Site\Requests\SiteAd\SiteAdDestroyRequest;
use App\Domains\Site\Requests\SiteAd\SiteAdQueryRequest;
use App\Domains\Site\Requests\SiteAd\SiteAdUpdateRequest;
use App\Domains\Site\Responses\SiteAd\SiteAdDestroyResponse;
use App\Domains\Site\Responses\SiteAd\SiteAdQueryResponse;
use App\Domains\Site\Responses\SiteAd\SiteAdResponse;
use App\Domains\Site\Services\SiteAdService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteAdController extends BaseController
{
    public function __construct(
        private readonly SiteAdService $siteAdService,
    ) {}

    #[OA\Post(path: '/siteAd/search', summary: '查询广告列表接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdQueryResponse::class),
        ],
    ))]
    public function search(SiteAdQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteAdQueryRequest::getPositionCode])) {
                $condition[] = [SiteAdEntity::getPositionCode, '=', $requestData[SiteAdQueryRequest::getPositionCode]];
            }
            if (isset($requestData[SiteAdQueryRequest::getId])) {
                $condition[] = [SiteAdEntity::getId, '=', $requestData[SiteAdQueryRequest::getId]];
            }

            $result = $this->siteAdService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteAdResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteAdQueryResponse::from($result);
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

    #[OA\Post(path: '/siteAd/store', summary: '新增广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdResponse::class),
        ],
    ))]
    public function store(SiteAdCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteAdEntity::from($requestData);

            if ($this->siteAdService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteAd/show', summary: '获取广告详情接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteAd = $this->siteAdService->getOneById($id);
            if (empty($siteAd)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteAdResponse::from($siteAd);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteAd/update', summary: '更新广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdResponse::class),
        ],
    ))]
    public function update(SiteAdUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteAd = $this->siteAdService->getOneById($id);
            if (empty($siteAd)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteAdEntity::from($requestData);

            $this->siteAdService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteAd/destroy', summary: '删除广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteAdDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteAdService->removeByIds($requestData['ids'])) {
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
