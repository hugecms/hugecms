<?php

declare(strict_types=1);

namespace App\Domains\Site\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Site\Entities\SiteDailyStatisticEntity;
use App\Domains\Site\Requests\SiteDailyStatistic\SiteDailyStatisticCreateRequest;
use App\Domains\Site\Requests\SiteDailyStatistic\SiteDailyStatisticDestroyRequest;
use App\Domains\Site\Requests\SiteDailyStatistic\SiteDailyStatisticQueryRequest;
use App\Domains\Site\Requests\SiteDailyStatistic\SiteDailyStatisticUpdateRequest;
use App\Domains\Site\Responses\SiteDailyStatistic\SiteDailyStatisticDestroyResponse;
use App\Domains\Site\Responses\SiteDailyStatistic\SiteDailyStatisticQueryResponse;
use App\Domains\Site\Responses\SiteDailyStatistic\SiteDailyStatisticResponse;
use App\Domains\Site\Services\SiteDailyStatisticService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SiteDailyStatisticController extends BaseController
{
    public function __construct(
        private readonly SiteDailyStatisticService $siteDailyStatisticService,
    ) {}

    #[OA\Post(path: '/siteDailyStatistic/search', summary: '查询每日统计汇总列表接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteDailyStatisticQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDailyStatisticQueryResponse::class),
        ],
    ))]
    public function search(SiteDailyStatisticQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SiteDailyStatisticQueryRequest::getId])) {
                $condition[] = [SiteDailyStatisticEntity::getId, '=', $requestData[SiteDailyStatisticQueryRequest::getId]];
            }
            if (isset($requestData[SiteDailyStatisticQueryRequest::getStatDate])) {
                $condition[] = [SiteDailyStatisticEntity::getStatDate, '=', $requestData[SiteDailyStatisticQueryRequest::getStatDate]];
            }

            $result = $this->siteDailyStatisticService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SiteDailyStatisticResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SiteDailyStatisticQueryResponse::from($result);
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

    #[OA\Post(path: '/siteDailyStatistic/store', summary: '新增每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteDailyStatisticCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDailyStatisticResponse::class),
        ],
    ))]
    public function store(SiteDailyStatisticCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SiteDailyStatisticEntity::from($requestData);

            if ($this->siteDailyStatisticService->save($input->toEntity())) {
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

    #[OA\Get(path: '/siteDailyStatistic/show', summary: '获取每日统计汇总详情接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDailyStatisticResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $siteDailyStatistic = $this->siteDailyStatisticService->getOneById($id);
            if (empty($siteDailyStatistic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SiteDailyStatisticResponse::from($siteDailyStatistic);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/siteDailyStatistic/update', summary: '更新每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteDailyStatisticUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDailyStatisticResponse::class),
        ],
    ))]
    public function update(SiteDailyStatisticUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $siteDailyStatistic = $this->siteDailyStatisticService->getOneById($id);
            if (empty($siteDailyStatistic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SiteDailyStatisticEntity::from($requestData);

            $this->siteDailyStatisticService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/siteDailyStatistic/destroy', summary: '删除每日统计汇总接口', security: [['bearerAuth' => []]], tags: ['每日统计汇总模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteDailyStatisticDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SiteDailyStatisticDestroyResponse::class),
        ],
    ))]
    public function destroy(SiteDailyStatisticDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->siteDailyStatisticService->removeByIds($requestData['ids'])) {
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
