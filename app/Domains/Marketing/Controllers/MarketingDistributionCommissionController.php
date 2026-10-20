<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingDistributionCommissionEntity;
use App\Domains\Marketing\Requests\MarketingDistributionCommission\MarketingDistributionCommissionCreateRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommission\MarketingDistributionCommissionDestroyRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommission\MarketingDistributionCommissionQueryRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommission\MarketingDistributionCommissionUpdateRequest;
use App\Domains\Marketing\Responses\MarketingDistributionCommission\MarketingDistributionCommissionDestroyResponse;
use App\Domains\Marketing\Responses\MarketingDistributionCommission\MarketingDistributionCommissionQueryResponse;
use App\Domains\Marketing\Responses\MarketingDistributionCommission\MarketingDistributionCommissionResponse;
use App\Domains\Marketing\Services\MarketingDistributionCommissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingDistributionCommissionController extends BaseController
{
    public function __construct(
        private readonly MarketingDistributionCommissionService $marketingDistributionCommissionService,
    ) {}

    #[OA\Post(path: '/marketingDistributionCommission/search', summary: '查询分销佣金流水列表接口', security: [['bearerAuth' => []]], tags: ['分销佣金流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionQueryResponse::class),
        ],
    ))]
    public function search(MarketingDistributionCommissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingDistributionCommissionQueryRequest::getDistributorId])) {
                $condition[] = [MarketingDistributionCommissionEntity::getDistributorId, '=', $requestData[MarketingDistributionCommissionQueryRequest::getDistributorId]];
            }
            if (isset($requestData[MarketingDistributionCommissionQueryRequest::getStatus])) {
                $condition[] = [MarketingDistributionCommissionEntity::getStatus, '=', $requestData[MarketingDistributionCommissionQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingDistributionCommissionQueryRequest::getOrderId])) {
                $condition[] = [MarketingDistributionCommissionEntity::getOrderId, '=', $requestData[MarketingDistributionCommissionQueryRequest::getOrderId]];
            }
            if (isset($requestData[MarketingDistributionCommissionQueryRequest::getId])) {
                $condition[] = [MarketingDistributionCommissionEntity::getId, '=', $requestData[MarketingDistributionCommissionQueryRequest::getId]];
            }

            $result = $this->marketingDistributionCommissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingDistributionCommissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingDistributionCommissionQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributionCommission/store', summary: '新增分销佣金流水接口', security: [['bearerAuth' => []]], tags: ['分销佣金流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionResponse::class),
        ],
    ))]
    public function store(MarketingDistributionCommissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingDistributionCommissionEntity::from($requestData);

            if ($this->marketingDistributionCommissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributionCommission/show', summary: '获取分销佣金流水详情接口', security: [['bearerAuth' => []]], tags: ['分销佣金流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributionCommission = $this->marketingDistributionCommissionService->getOneById($id);
            if (empty($marketingDistributionCommission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingDistributionCommissionResponse::from($marketingDistributionCommission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributionCommission/update', summary: '更新分销佣金流水接口', security: [['bearerAuth' => []]], tags: ['分销佣金流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionResponse::class),
        ],
    ))]
    public function update(MarketingDistributionCommissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributionCommission = $this->marketingDistributionCommissionService->getOneById($id);
            if (empty($marketingDistributionCommission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingDistributionCommissionEntity::from($requestData);

            $this->marketingDistributionCommissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributionCommission/destroy', summary: '删除分销佣金流水接口', security: [['bearerAuth' => []]], tags: ['分销佣金流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingDistributionCommissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributionCommissionService->removeByIds($requestData['ids'])) {
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
