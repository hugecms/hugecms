<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteStatisticsDailyEntity;
use App\Domains\Site\Requests\SiteStatisticsDaily\SiteStatisticsDailyCreateRequest;
use App\Domains\Site\Requests\SiteStatisticsDaily\SiteStatisticsDailyDestroyRequest;
use App\Domains\Site\Requests\SiteStatisticsDaily\SiteStatisticsDailyQueryRequest;
use App\Domains\Site\Requests\SiteStatisticsDaily\SiteStatisticsDailyUpdateRequest;
use App\Domains\Site\Responses\SiteStatisticsDaily\SiteStatisticsDailyDestroyResponse;
use App\Domains\Site\Responses\SiteStatisticsDaily\SiteStatisticsDailyQueryResponse;
use App\Domains\Site\Responses\SiteStatisticsDaily\SiteStatisticsDailyResponse;
use App\Domains\Site\Services\SiteStatisticsDailyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteStatisticsDailyController extends BaseController
{
    public function __construct(
        private readonly SiteStatisticsDailyService $siteStatisticsDailyService,
    ) {}

    #[OA\Post(path: '/siteStatisticsDaily/search', summary: '查询每日统计汇总列表接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteStatisticsDailyQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteStatisticsDailyQueryResponse::class),
        ],
    ))]
    public function search(SiteStatisticsDailyQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteStatisticsDailyQueryRequest::getId])) {
                $condition[] = [SiteStatisticsDailyEntity::getId, '=', $requestData[SiteStatisticsDailyQueryRequest::getId]];
            }
            if (isset($requestData[SiteStatisticsDailyQueryRequest::getStatDate])) {
                $condition[] = [SiteStatisticsDailyEntity::getStatDate, '=', $requestData[SiteStatisticsDailyQueryRequest::getStatDate]];
            }

            $result = $this->siteStatisticsDailyService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteStatisticsDailyResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteStatisticsDailyQueryResponse::from($result);
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

    #[OA\Post(path: '/siteStatisticsDaily/store', summary: '新增每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteStatisticsDailyCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteStatisticsDailyResponse::class),
        ],
    ))]
    public function store(SiteStatisticsDailyCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteStatisticsDailyEntity::from($requestData);

            if ($this->siteStatisticsDailyService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteStatisticsDaily/show', summary: '获取每日统计汇总详情接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteStatisticsDailyResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteStatisticsDaily = $this->siteStatisticsDailyService->getOneById($id);
            if (empty($siteStatisticsDaily)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteStatisticsDailyResponse::from($siteStatisticsDaily);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteStatisticsDaily/update', summary: '更新每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteStatisticsDailyUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteStatisticsDailyResponse::class),
        ],
    ))]
    public function update(SiteStatisticsDailyUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteStatisticsDaily = $this->siteStatisticsDailyService->getOneById($id);
            if (empty($siteStatisticsDaily)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteStatisticsDailyEntity::from($requestData);

            $this->siteStatisticsDailyService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteStatisticsDaily/destroy', summary: '删除每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteStatisticsDailyDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteStatisticsDailyDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteStatisticsDailyDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteStatisticsDailyService->removeByIds($requestData['ids'])) {
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
