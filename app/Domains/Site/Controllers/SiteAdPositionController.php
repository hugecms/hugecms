<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteAdPositionEntity;
use App\Domains\Site\Requests\SiteAdPosition\SiteAdPositionCreateRequest;
use App\Domains\Site\Requests\SiteAdPosition\SiteAdPositionDestroyRequest;
use App\Domains\Site\Requests\SiteAdPosition\SiteAdPositionQueryRequest;
use App\Domains\Site\Requests\SiteAdPosition\SiteAdPositionUpdateRequest;
use App\Domains\Site\Responses\SiteAdPosition\SiteAdPositionDestroyResponse;
use App\Domains\Site\Responses\SiteAdPosition\SiteAdPositionQueryResponse;
use App\Domains\Site\Responses\SiteAdPosition\SiteAdPositionResponse;
use App\Domains\Site\Services\SiteAdPositionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteAdPositionController extends BaseController
{
    public function __construct(
        private readonly SiteAdPositionService $siteAdPositionService,
    ) {}

    #[OA\Post(path: '/siteAdPosition/search', summary: '查询广告位定义列表接口', security: [['bearerAuth' => []]], tags: ['广告位定义模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdPositionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdPositionQueryResponse::class),
        ],
    ))]
    public function search(SiteAdPositionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteAdPositionQueryRequest::getId])) {
                $condition[] = [SiteAdPositionEntity::getId, '=', $requestData[SiteAdPositionQueryRequest::getId]];
            }
            if (isset($requestData[SiteAdPositionQueryRequest::getCode])) {
                $condition[] = [SiteAdPositionEntity::getCode, '=', $requestData[SiteAdPositionQueryRequest::getCode]];
            }

            $result = $this->siteAdPositionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteAdPositionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteAdPositionQueryResponse::from($result);
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

    #[OA\Post(path: '/siteAdPosition/store', summary: '新增广告位定义接口', security: [['bearerAuth' => []]], tags: ['广告位定义模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdPositionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdPositionResponse::class),
        ],
    ))]
    public function store(SiteAdPositionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteAdPositionEntity::from($requestData);

            if ($this->siteAdPositionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteAdPosition/show', summary: '获取广告位定义详情接口', security: [['bearerAuth' => []]], tags: ['广告位定义模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdPositionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteAdPosition = $this->siteAdPositionService->getOneById($id);
            if (empty($siteAdPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteAdPositionResponse::from($siteAdPosition);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteAdPosition/update', summary: '更新广告位定义接口', security: [['bearerAuth' => []]], tags: ['广告位定义模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdPositionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdPositionResponse::class),
        ],
    ))]
    public function update(SiteAdPositionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteAdPosition = $this->siteAdPositionService->getOneById($id);
            if (empty($siteAdPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteAdPositionEntity::from($requestData);

            $this->siteAdPositionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteAdPosition/destroy', summary: '删除广告位定义接口', security: [['bearerAuth' => []]], tags: ['广告位定义模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteAdPositionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteAdPositionDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteAdPositionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteAdPositionService->removeByIds($requestData['ids'])) {
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
