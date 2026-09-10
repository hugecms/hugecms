<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\SiteEntity;
use App\Services\SiteService;
use App\Api\Admin\Requests\Site\SiteCreateRequest;
use App\Api\Admin\Requests\Site\SiteDestroyRequest;
use App\Api\Admin\Requests\Site\SiteQueryRequest;
use App\Api\Admin\Requests\Site\SiteUpdateRequest;
use App\Api\Admin\Responses\Site\SiteDestroyResponse;
use App\Api\Admin\Responses\Site\SiteQueryResponse;
use App\Api\Admin\Responses\Site\SiteResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteController extends BaseController
{
    public function __construct(
        private readonly SiteService $siteService,
    ) {}

    #[OA\Post(path: '/site/search', summary: '查询站点列表接口', security: [['bearerAuth' => []]], tags: ['站点模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteQueryResponse::class),
        ],
    ))]
    public function search(SiteQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteQueryRequest::getId])) {
                $condition[] = [SiteEntity::getId, '=', $requestData[SiteQueryRequest::getId]];
            }
            if (isset($requestData[SiteQueryRequest::getDomain])) {
                $condition[] = [SiteEntity::getDomain, '=', $requestData[SiteQueryRequest::getDomain]];
            }
            if (isset($requestData[SiteQueryRequest::getSiteCode])) {
                $condition[] = [SiteEntity::getSiteCode, '=', $requestData[SiteQueryRequest::getSiteCode]];
            }
            
            $result = $this->siteService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteQueryResponse::from($result);
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

    #[OA\Post(path: '/site/store', summary: '新增站点接口', security: [['bearerAuth' => []]], tags: ['站点模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteResponse::class),
        ],
    ))]
    public function store(SiteCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteEntity::from($requestData);

            if ($this->siteService->save($input->toEntity())) {
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

    #[OA\Get(path: '/site/show', summary: '获取站点详情接口', security: [['bearerAuth' => []]], tags: ['站点模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $site = $this->siteService->getOneById($id);
            if (empty($site)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteResponse::from($site);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/site/update', summary: '更新站点接口', security: [['bearerAuth' => []]], tags: ['站点模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteResponse::class),
        ],
    ))]
    public function update(SiteUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $site = $this->siteService->getOneById($id);
            if (empty($site)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteEntity::from($requestData);

            $this->siteService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/site/destroy', summary: '删除站点接口', security: [['bearerAuth' => []]], tags: ['站点模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteService->removeByIds($requestData['ids'])) {
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
