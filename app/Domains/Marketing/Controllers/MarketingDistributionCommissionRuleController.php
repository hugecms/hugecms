<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingDistributionCommissionRuleEntity;
use App\Domains\Marketing\Requests\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleCreateRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleDestroyRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleQueryRequest;
use App\Domains\Marketing\Requests\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleUpdateRequest;
use App\Domains\Marketing\Responses\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleDestroyResponse;
use App\Domains\Marketing\Responses\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleQueryResponse;
use App\Domains\Marketing\Responses\MarketingDistributionCommissionRule\MarketingDistributionCommissionRuleResponse;
use App\Domains\Marketing\Services\MarketingDistributionCommissionRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingDistributionCommissionRuleController extends BaseController
{
    public function __construct(
        private readonly MarketingDistributionCommissionRuleService $marketingDistributionCommissionRuleService,
    ) {}

    #[OA\Post(path: '/marketingDistributionCommissionRule/search', summary: '查询佣金规则列表接口', security: [['bearerAuth' => []]], tags: ['佣金规则模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionRuleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionRuleQueryResponse::class),
        ],
    ))]
    public function search(MarketingDistributionCommissionRuleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingDistributionCommissionRuleQueryRequest::getStatus])) {
                $condition[] = [MarketingDistributionCommissionRuleEntity::getStatus, '=', $requestData[MarketingDistributionCommissionRuleQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingDistributionCommissionRuleQueryRequest::getId])) {
                $condition[] = [MarketingDistributionCommissionRuleEntity::getId, '=', $requestData[MarketingDistributionCommissionRuleQueryRequest::getId]];
            }
            if (isset($requestData[MarketingDistributionCommissionRuleQueryRequest::getScopeType])) {
                $condition[] = [MarketingDistributionCommissionRuleEntity::getScopeType, '=', $requestData[MarketingDistributionCommissionRuleQueryRequest::getScopeType]];
            }
            if (isset($requestData[MarketingDistributionCommissionRuleQueryRequest::getScopeId])) {
                $condition[] = [MarketingDistributionCommissionRuleEntity::getScopeId, '=', $requestData[MarketingDistributionCommissionRuleQueryRequest::getScopeId]];
            }

            $result = $this->marketingDistributionCommissionRuleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingDistributionCommissionRuleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingDistributionCommissionRuleQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributionCommissionRule/store', summary: '新增佣金规则接口', security: [['bearerAuth' => []]], tags: ['佣金规则模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionRuleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionRuleResponse::class),
        ],
    ))]
    public function store(MarketingDistributionCommissionRuleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingDistributionCommissionRuleEntity::from($requestData);

            if ($this->marketingDistributionCommissionRuleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributionCommissionRule/show', summary: '获取佣金规则详情接口', security: [['bearerAuth' => []]], tags: ['佣金规则模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionRuleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributionCommissionRule = $this->marketingDistributionCommissionRuleService->getOneById($id);
            if (empty($marketingDistributionCommissionRule)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingDistributionCommissionRuleResponse::from($marketingDistributionCommissionRule);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributionCommissionRule/update', summary: '更新佣金规则接口', security: [['bearerAuth' => []]], tags: ['佣金规则模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionRuleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionRuleResponse::class),
        ],
    ))]
    public function update(MarketingDistributionCommissionRuleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributionCommissionRule = $this->marketingDistributionCommissionRuleService->getOneById($id);
            if (empty($marketingDistributionCommissionRule)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingDistributionCommissionRuleEntity::from($requestData);

            $this->marketingDistributionCommissionRuleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributionCommissionRule/destroy', summary: '删除佣金规则接口', security: [['bearerAuth' => []]], tags: ['佣金规则模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionCommissionRuleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionCommissionRuleDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingDistributionCommissionRuleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributionCommissionRuleService->removeByIds($requestData['ids'])) {
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
