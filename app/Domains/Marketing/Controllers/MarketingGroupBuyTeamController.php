<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingGroupBuyTeamEntity;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeam\MarketingGroupBuyTeamCreateRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeam\MarketingGroupBuyTeamDestroyRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeam\MarketingGroupBuyTeamQueryRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeam\MarketingGroupBuyTeamUpdateRequest;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeam\MarketingGroupBuyTeamDestroyResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeam\MarketingGroupBuyTeamQueryResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeam\MarketingGroupBuyTeamResponse;
use App\Domains\Marketing\Services\MarketingGroupBuyTeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingGroupBuyTeamController extends BaseController
{
    public function __construct(
        private readonly MarketingGroupBuyTeamService $marketingGroupBuyTeamService,
    ) {}

    #[OA\Post(path: '/marketingGroupBuyTeam/search', summary: '查询拼团团列表接口', security: [['bearerAuth' => []]], tags: ['拼团团模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamQueryResponse::class),
        ],
    ))]
    public function search(MarketingGroupBuyTeamQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getActivityId])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getActivityId, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getActivityId]];
            }
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getStatus])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getStatus, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getLeaderUserId])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getLeaderUserId, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getLeaderUserId]];
            }
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getStatus])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getStatus, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getExpireTime])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getExpireTime, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getExpireTime]];
            }
            if (isset($requestData[MarketingGroupBuyTeamQueryRequest::getId])) {
                $condition[] = [MarketingGroupBuyTeamEntity::getId, '=', $requestData[MarketingGroupBuyTeamQueryRequest::getId]];
            }

            $result = $this->marketingGroupBuyTeamService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingGroupBuyTeamResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingGroupBuyTeamQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingGroupBuyTeam/store', summary: '新增拼团团接口', security: [['bearerAuth' => []]], tags: ['拼团团模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamResponse::class),
        ],
    ))]
    public function store(MarketingGroupBuyTeamCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingGroupBuyTeamEntity::from($requestData);

            if ($this->marketingGroupBuyTeamService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingGroupBuyTeam/show', summary: '获取拼团团详情接口', security: [['bearerAuth' => []]], tags: ['拼团团模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingGroupBuyTeam = $this->marketingGroupBuyTeamService->getOneById($id);
            if (empty($marketingGroupBuyTeam)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingGroupBuyTeamResponse::from($marketingGroupBuyTeam);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingGroupBuyTeam/update', summary: '更新拼团团接口', security: [['bearerAuth' => []]], tags: ['拼团团模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamResponse::class),
        ],
    ))]
    public function update(MarketingGroupBuyTeamUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingGroupBuyTeam = $this->marketingGroupBuyTeamService->getOneById($id);
            if (empty($marketingGroupBuyTeam)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingGroupBuyTeamEntity::from($requestData);

            $this->marketingGroupBuyTeamService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingGroupBuyTeam/destroy', summary: '删除拼团团接口', security: [['bearerAuth' => []]], tags: ['拼团团模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingGroupBuyTeamDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingGroupBuyTeamService->removeByIds($requestData['ids'])) {
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
