<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingFlashSaleLogEntity;
use App\Domains\Marketing\Requests\MarketingFlashSaleLog\MarketingFlashSaleLogCreateRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleLog\MarketingFlashSaleLogDestroyRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleLog\MarketingFlashSaleLogQueryRequest;
use App\Domains\Marketing\Requests\MarketingFlashSaleLog\MarketingFlashSaleLogUpdateRequest;
use App\Domains\Marketing\Responses\MarketingFlashSaleLog\MarketingFlashSaleLogDestroyResponse;
use App\Domains\Marketing\Responses\MarketingFlashSaleLog\MarketingFlashSaleLogQueryResponse;
use App\Domains\Marketing\Responses\MarketingFlashSaleLog\MarketingFlashSaleLogResponse;
use App\Domains\Marketing\Services\MarketingFlashSaleLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingFlashSaleLogController extends BaseController
{
    public function __construct(
        private readonly MarketingFlashSaleLogService $marketingFlashSaleLogService,
    ) {}

    #[OA\Post(path: '/marketingFlashSaleLog/search', summary: '查询秒杀库存流水列表接口', security: [['bearerAuth' => []]], tags: ['秒杀库存流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleLogQueryResponse::class),
        ],
    ))]
    public function search(MarketingFlashSaleLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingFlashSaleLogQueryRequest::getFlashSaleItemId])) {
                $condition[] = [MarketingFlashSaleLogEntity::getFlashSaleItemId, '=', $requestData[MarketingFlashSaleLogQueryRequest::getFlashSaleItemId]];
            }
            if (isset($requestData[MarketingFlashSaleLogQueryRequest::getOrderId])) {
                $condition[] = [MarketingFlashSaleLogEntity::getOrderId, '=', $requestData[MarketingFlashSaleLogQueryRequest::getOrderId]];
            }
            if (isset($requestData[MarketingFlashSaleLogQueryRequest::getId])) {
                $condition[] = [MarketingFlashSaleLogEntity::getId, '=', $requestData[MarketingFlashSaleLogQueryRequest::getId]];
            }

            $result = $this->marketingFlashSaleLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingFlashSaleLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingFlashSaleLogQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingFlashSaleLog/store', summary: '新增秒杀库存流水接口', security: [['bearerAuth' => []]], tags: ['秒杀库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleLogResponse::class),
        ],
    ))]
    public function store(MarketingFlashSaleLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingFlashSaleLogEntity::from($requestData);

            if ($this->marketingFlashSaleLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingFlashSaleLog/show', summary: '获取秒杀库存流水详情接口', security: [['bearerAuth' => []]], tags: ['秒杀库存流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingFlashSaleLog = $this->marketingFlashSaleLogService->getOneById($id);
            if (empty($marketingFlashSaleLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingFlashSaleLogResponse::from($marketingFlashSaleLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingFlashSaleLog/update', summary: '更新秒杀库存流水接口', security: [['bearerAuth' => []]], tags: ['秒杀库存流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleLogResponse::class),
        ],
    ))]
    public function update(MarketingFlashSaleLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingFlashSaleLog = $this->marketingFlashSaleLogService->getOneById($id);
            if (empty($marketingFlashSaleLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingFlashSaleLogEntity::from($requestData);

            $this->marketingFlashSaleLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingFlashSaleLog/destroy', summary: '删除秒杀库存流水接口', security: [['bearerAuth' => []]], tags: ['秒杀库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleLogDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingFlashSaleLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingFlashSaleLogService->removeByIds($requestData['ids'])) {
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
