<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteNavEntity;
use App\Domains\Site\Requests\SiteNav\SiteNavCreateRequest;
use App\Domains\Site\Requests\SiteNav\SiteNavDestroyRequest;
use App\Domains\Site\Requests\SiteNav\SiteNavQueryRequest;
use App\Domains\Site\Requests\SiteNav\SiteNavUpdateRequest;
use App\Domains\Site\Responses\SiteNav\SiteNavDestroyResponse;
use App\Domains\Site\Responses\SiteNav\SiteNavQueryResponse;
use App\Domains\Site\Responses\SiteNav\SiteNavResponse;
use App\Domains\Site\Services\SiteNavService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteNavController extends BaseController
{
    public function __construct(
        private readonly SiteNavService $siteNavService,
    ) {}

    #[OA\Post(path: '/siteNav/search', summary: '查询导航列表接口', security: [['bearerAuth' => []]], tags: ['导航模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteNavQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteNavQueryResponse::class),
        ],
    ))]
    public function search(SiteNavQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteNavQueryRequest::getPosition])) {
                $condition[] = [SiteNavEntity::getPosition, '=', $requestData[SiteNavQueryRequest::getPosition]];
            }
            if (isset($requestData[SiteNavQueryRequest::getId])) {
                $condition[] = [SiteNavEntity::getId, '=', $requestData[SiteNavQueryRequest::getId]];
            }

            $result = $this->siteNavService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteNavResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteNavQueryResponse::from($result);
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

    #[OA\Post(path: '/siteNav/store', summary: '新增导航接口', security: [['bearerAuth' => []]], tags: ['导航模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteNavCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteNavResponse::class),
        ],
    ))]
    public function store(SiteNavCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteNavEntity::from($requestData);

            if ($this->siteNavService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteNav/show', summary: '获取导航详情接口', security: [['bearerAuth' => []]], tags: ['导航模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteNavResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteNav = $this->siteNavService->getOneById($id);
            if (empty($siteNav)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteNavResponse::from($siteNav);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteNav/update', summary: '更新导航接口', security: [['bearerAuth' => []]], tags: ['导航模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteNavUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteNavResponse::class),
        ],
    ))]
    public function update(SiteNavUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteNav = $this->siteNavService->getOneById($id);
            if (empty($siteNav)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteNavEntity::from($requestData);

            $this->siteNavService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteNav/destroy', summary: '删除导航接口', security: [['bearerAuth' => []]], tags: ['导航模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteNavDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteNavDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteNavDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteNavService->removeByIds($requestData['ids'])) {
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
