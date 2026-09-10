<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\StatisticsDailyEntity;
use App\Services\StatisticsDailyService;
use App\Api\Admin\Requests\StatisticsDaily\StatisticsDailyCreateRequest;
use App\Api\Admin\Requests\StatisticsDaily\StatisticsDailyDestroyRequest;
use App\Api\Admin\Requests\StatisticsDaily\StatisticsDailyQueryRequest;
use App\Api\Admin\Requests\StatisticsDaily\StatisticsDailyUpdateRequest;
use App\Api\Admin\Responses\StatisticsDaily\StatisticsDailyDestroyResponse;
use App\Api\Admin\Responses\StatisticsDaily\StatisticsDailyQueryResponse;
use App\Api\Admin\Responses\StatisticsDaily\StatisticsDailyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class StatisticsDailyController extends BaseController
{
    public function __construct(
        private readonly StatisticsDailyService $statisticsDailyService,
    ) {}

    #[OA\Post(path: '/statisticsDaily/search', summary: '查询每日统计列表接口', security: [['bearerAuth' => []]], tags: ['每日统计模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StatisticsDailyQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: StatisticsDailyQueryResponse::class),
        ],
    ))]
    public function search(StatisticsDailyQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[StatisticsDailyQueryRequest::getId])) {
                $condition[] = [StatisticsDailyEntity::getId, '=', $requestData[StatisticsDailyQueryRequest::getId]];
            }
            if (isset($requestData[StatisticsDailyQueryRequest::getStatDate])) {
                $condition[] = [StatisticsDailyEntity::getStatDate, '=', $requestData[StatisticsDailyQueryRequest::getStatDate]];
            }
            
            $result = $this->statisticsDailyService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = StatisticsDailyResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = StatisticsDailyQueryResponse::from($result);
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

    #[OA\Post(path: '/statisticsDaily/store', summary: '新增每日统计接口', security: [['bearerAuth' => []]], tags: ['每日统计模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StatisticsDailyCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: StatisticsDailyResponse::class),
        ],
    ))]
    public function store(StatisticsDailyCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = StatisticsDailyEntity::from($requestData);

            if ($this->statisticsDailyService->save($input->toEntity())) {
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

    #[OA\Get(path: '/statisticsDaily/show', summary: '获取每日统计详情接口', security: [['bearerAuth' => []]], tags: ['每日统计模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: StatisticsDailyResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $statisticsDaily = $this->statisticsDailyService->getOneById($id);
            if (empty($statisticsDaily)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = StatisticsDailyResponse::from($statisticsDaily);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/statisticsDaily/update', summary: '更新每日统计接口', security: [['bearerAuth' => []]], tags: ['每日统计模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StatisticsDailyUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: StatisticsDailyResponse::class),
        ],
    ))]
    public function update(StatisticsDailyUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $statisticsDaily = $this->statisticsDailyService->getOneById($id);
            if (empty($statisticsDaily)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = StatisticsDailyEntity::from($requestData);

            $this->statisticsDailyService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/statisticsDaily/destroy', summary: '删除每日统计接口', security: [['bearerAuth' => []]], tags: ['每日统计模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StatisticsDailyDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: StatisticsDailyDestroyResponse::class),
        ],
    ))]
    public function destroy(StatisticsDailyDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->statisticsDailyService->removeByIds($requestData['ids'])) {
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
