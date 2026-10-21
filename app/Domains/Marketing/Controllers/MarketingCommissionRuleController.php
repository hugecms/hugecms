<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingCommissionRuleEntity;
use App\Domains\Marketing\Requests\MarketingCommissionRule\MarketingCommissionRuleCreateRequest;
use App\Domains\Marketing\Requests\MarketingCommissionRule\MarketingCommissionRuleDestroyRequest;
use App\Domains\Marketing\Requests\MarketingCommissionRule\MarketingCommissionRuleQueryRequest;
use App\Domains\Marketing\Requests\MarketingCommissionRule\MarketingCommissionRuleUpdateRequest;
use App\Domains\Marketing\Responses\MarketingCommissionRule\MarketingCommissionRuleDestroyResponse;
use App\Domains\Marketing\Responses\MarketingCommissionRule\MarketingCommissionRuleQueryResponse;
use App\Domains\Marketing\Responses\MarketingCommissionRule\MarketingCommissionRuleResponse;
use App\Domains\Marketing\Services\MarketingCommissionRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingCommissionRuleController extends BaseController
{
    public function __construct(
        private readonly MarketingCommissionRuleService $marketingDistributionUserService,
    ) {}

    #[OA\Post(path: '/marketingDistributionUser/search', summary: '查询分销员列表接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCommissionRuleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCommissionRuleQueryResponse::class),
        ],
    ))]
    public function search(MarketingCommissionRuleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingCommissionRuleQueryRequest::getInviterId])) {
                $condition[] = [MarketingCommissionRuleEntity::getInviterId, '=', $requestData[MarketingCommissionRuleQueryRequest::getInviterId]];
            }
            if (isset($requestData[MarketingCommissionRuleQueryRequest::getId])) {
                $condition[] = [MarketingCommissionRuleEntity::getId, '=', $requestData[MarketingCommissionRuleQueryRequest::getId]];
            }
            if (isset($requestData[MarketingCommissionRuleQueryRequest::getUserId])) {
                $condition[] = [MarketingCommissionRuleEntity::getUserId, '=', $requestData[MarketingCommissionRuleQueryRequest::getUserId]];
            }

            $result = $this->marketingDistributionUserService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingCommissionRuleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingCommissionRuleQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributionUser/store', summary: '新增分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCommissionRuleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCommissionRuleResponse::class),
        ],
    ))]
    public function store(MarketingCommissionRuleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingCommissionRuleEntity::from($requestData);

            if ($this->marketingDistributionUserService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributionUser/show', summary: '获取分销员详情接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCommissionRuleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributionUser = $this->marketingDistributionUserService->getOneById($id);
            if (empty($marketingDistributionUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingCommissionRuleResponse::from($marketingDistributionUser);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributionUser/update', summary: '更新分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCommissionRuleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCommissionRuleResponse::class),
        ],
    ))]
    public function update(MarketingCommissionRuleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributionUser = $this->marketingDistributionUserService->getOneById($id);
            if (empty($marketingDistributionUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingCommissionRuleEntity::from($requestData);

            $this->marketingDistributionUserService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributionUser/destroy', summary: '删除分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCommissionRuleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCommissionRuleDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingCommissionRuleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributionUserService->removeByIds($requestData['ids'])) {
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
