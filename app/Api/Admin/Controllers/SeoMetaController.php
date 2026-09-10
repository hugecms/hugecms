<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\SeoMetaEntity;
use App\Services\SeoMetaService;
use App\Api\Admin\Requests\SeoMeta\SeoMetaCreateRequest;
use App\Api\Admin\Requests\SeoMeta\SeoMetaDestroyRequest;
use App\Api\Admin\Requests\SeoMeta\SeoMetaQueryRequest;
use App\Api\Admin\Requests\SeoMeta\SeoMetaUpdateRequest;
use App\Api\Admin\Responses\SeoMeta\SeoMetaDestroyResponse;
use App\Api\Admin\Responses\SeoMeta\SeoMetaQueryResponse;
use App\Api\Admin\Responses\SeoMeta\SeoMetaResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SeoMetaController extends BaseController
{
    public function __construct(
        private readonly SeoMetaService $seoMetaService,
    ) {}

    #[OA\Post(path: '/seoMeta/search', summary: '查询SEO元数据列表接口', security: [['bearerAuth' => []]], tags: ['SEO元数据模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeoMetaQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SeoMetaQueryResponse::class),
        ],
    ))]
    public function search(SeoMetaQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SeoMetaQueryRequest::getId])) {
                $condition[] = [SeoMetaEntity::getId, '=', $requestData[SeoMetaQueryRequest::getId]];
            }
            if (isset($requestData[SeoMetaQueryRequest::getTargetId])) {
                $condition[] = [SeoMetaEntity::getTargetId, '=', $requestData[SeoMetaQueryRequest::getTargetId]];
            }
            if (isset($requestData[SeoMetaQueryRequest::getTargetId])) {
                $condition[] = [SeoMetaEntity::getTargetId, '=', $requestData[SeoMetaQueryRequest::getTargetId]];
            }
            
            $result = $this->seoMetaService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SeoMetaResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SeoMetaQueryResponse::from($result);
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

    #[OA\Post(path: '/seoMeta/store', summary: '新增SEO元数据接口', security: [['bearerAuth' => []]], tags: ['SEO元数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeoMetaCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SeoMetaResponse::class),
        ],
    ))]
    public function store(SeoMetaCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SeoMetaEntity::from($requestData);

            if ($this->seoMetaService->save($input->toEntity())) {
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

    #[OA\Get(path: '/seoMeta/show', summary: '获取SEO元数据详情接口', security: [['bearerAuth' => []]], tags: ['SEO元数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SeoMetaResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $seoMeta = $this->seoMetaService->getOneById($id);
            if (empty($seoMeta)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SeoMetaResponse::from($seoMeta);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/seoMeta/update', summary: '更新SEO元数据接口', security: [['bearerAuth' => []]], tags: ['SEO元数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeoMetaUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SeoMetaResponse::class),
        ],
    ))]
    public function update(SeoMetaUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $seoMeta = $this->seoMetaService->getOneById($id);
            if (empty($seoMeta)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SeoMetaEntity::from($requestData);

            $this->seoMetaService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/seoMeta/destroy', summary: '删除SEO元数据接口', security: [['bearerAuth' => []]], tags: ['SEO元数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeoMetaDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SeoMetaDestroyResponse::class),
        ],
    ))]
    public function destroy(SeoMetaDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->seoMetaService->removeByIds($requestData['ids'])) {
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
