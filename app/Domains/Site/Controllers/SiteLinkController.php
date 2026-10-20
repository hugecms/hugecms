<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteLinkEntity;
use App\Domains\Site\Requests\SiteLink\SiteLinkCreateRequest;
use App\Domains\Site\Requests\SiteLink\SiteLinkDestroyRequest;
use App\Domains\Site\Requests\SiteLink\SiteLinkQueryRequest;
use App\Domains\Site\Requests\SiteLink\SiteLinkUpdateRequest;
use App\Domains\Site\Responses\SiteLink\SiteLinkDestroyResponse;
use App\Domains\Site\Responses\SiteLink\SiteLinkQueryResponse;
use App\Domains\Site\Responses\SiteLink\SiteLinkResponse;
use App\Domains\Site\Services\SiteLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteLinkController extends BaseController
{
    public function __construct(
        private readonly SiteLinkService $siteLinkService,
    ) {}

    #[OA\Post(path: '/siteLink/search', summary: '查询友情链接列表接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteLinkQueryResponse::class),
        ],
    ))]
    public function search(SiteLinkQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteLinkQueryRequest::getId])) {
                $condition[] = [SiteLinkEntity::getId, '=', $requestData[SiteLinkQueryRequest::getId]];
            }

            $result = $this->siteLinkService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteLinkResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteLinkQueryResponse::from($result);
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

    #[OA\Post(path: '/siteLink/store', summary: '新增友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteLinkResponse::class),
        ],
    ))]
    public function store(SiteLinkCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteLinkEntity::from($requestData);

            if ($this->siteLinkService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteLink/show', summary: '获取友情链接详情接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteLinkResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteLink = $this->siteLinkService->getOneById($id);
            if (empty($siteLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteLinkResponse::from($siteLink);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteLink/update', summary: '更新友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteLinkResponse::class),
        ],
    ))]
    public function update(SiteLinkUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteLink = $this->siteLinkService->getOneById($id);
            if (empty($siteLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteLinkEntity::from($requestData);

            $this->siteLinkService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteLink/destroy', summary: '删除友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteLinkDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteLinkDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteLinkService->removeByIds($requestData['ids'])) {
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
