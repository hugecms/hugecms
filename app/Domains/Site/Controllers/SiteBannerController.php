<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteBannerEntity;
use App\Domains\Site\Services\SiteBannerService;
use App\Domains\Site\Requests\SiteBanner\SiteBannerCreateRequest;
use App\Domains\Site\Requests\SiteBanner\SiteBannerDestroyRequest;
use App\Domains\Site\Requests\SiteBanner\SiteBannerQueryRequest;
use App\Domains\Site\Requests\SiteBanner\SiteBannerUpdateRequest;
use App\Domains\Site\Responses\SiteBanner\SiteBannerDestroyResponse;
use App\Domains\Site\Responses\SiteBanner\SiteBannerQueryResponse;
use App\Domains\Site\Responses\SiteBanner\SiteBannerResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteBannerController extends BaseController
{
    public function __construct(
        private readonly SiteBannerService $siteBannerService,
    ) {}

    #[OA\Post(path: '/siteBanner/search', summary: '查询轮播图/广告列表接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteBannerQueryResponse::class),
        ],
    ))]
    public function search(SiteBannerQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteBannerQueryRequest::getPosition])) {
                $condition[] = [SiteBannerEntity::getPosition, '=', $requestData[SiteBannerQueryRequest::getPosition]];
            }
            if (isset($requestData[SiteBannerQueryRequest::getId])) {
                $condition[] = [SiteBannerEntity::getId, '=', $requestData[SiteBannerQueryRequest::getId]];
            }
            
            $result = $this->siteBannerService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteBannerResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteBannerQueryResponse::from($result);
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

    #[OA\Post(path: '/siteBanner/store', summary: '新增轮播图/广告接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteBannerResponse::class),
        ],
    ))]
    public function store(SiteBannerCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteBannerEntity::from($requestData);

            if ($this->siteBannerService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteBanner/show', summary: '获取轮播图/广告详情接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteBannerResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteBanner = $this->siteBannerService->getOneById($id);
            if (empty($siteBanner)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteBannerResponse::from($siteBanner);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteBanner/update', summary: '更新轮播图/广告接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteBannerResponse::class),
        ],
    ))]
    public function update(SiteBannerUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteBanner = $this->siteBannerService->getOneById($id);
            if (empty($siteBanner)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteBannerEntity::from($requestData);

            $this->siteBannerService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteBanner/destroy', summary: '删除轮播图/广告接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteBannerDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteBannerDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteBannerService->removeByIds($requestData['ids'])) {
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
