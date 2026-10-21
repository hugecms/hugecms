<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingGroupBuyTeamMemberEntity;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberCreateRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberDestroyRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberQueryRequest;
use App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberUpdateRequest;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberDestroyResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberQueryResponse;
use App\Domains\Marketing\Responses\MarketingGroupBuyTeamMember\MarketingGroupBuyTeamMemberResponse;
use App\Domains\Marketing\Services\MarketingGroupBuyTeamMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingGroupBuyTeamMemberController extends BaseController
{
    public function __construct(
        private readonly MarketingGroupBuyTeamMemberService $marketingGroupBuyTeamMemberService,
    ) {}

    #[OA\Post(path: '/marketingGroupBuyTeamMember/search', summary: '查询拼团团员列表接口', security: [['bearerAuth' => []]], tags: ['拼团团员模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamMemberQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamMemberQueryResponse::class),
        ],
    ))]
    public function search(MarketingGroupBuyTeamMemberQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingGroupBuyTeamMemberQueryRequest::getUserId])) {
                $condition[] = [MarketingGroupBuyTeamMemberEntity::getUserId, '=', $requestData[MarketingGroupBuyTeamMemberQueryRequest::getUserId]];
            }
            if (isset($requestData[MarketingGroupBuyTeamMemberQueryRequest::getId])) {
                $condition[] = [MarketingGroupBuyTeamMemberEntity::getId, '=', $requestData[MarketingGroupBuyTeamMemberQueryRequest::getId]];
            }
            if (isset($requestData[MarketingGroupBuyTeamMemberQueryRequest::getOrderNo])) {
                $condition[] = [MarketingGroupBuyTeamMemberEntity::getOrderNo, '=', $requestData[MarketingGroupBuyTeamMemberQueryRequest::getOrderNo]];
            }
            if (isset($requestData[MarketingGroupBuyTeamMemberQueryRequest::getTeamId])) {
                $condition[] = [MarketingGroupBuyTeamMemberEntity::getTeamId, '=', $requestData[MarketingGroupBuyTeamMemberQueryRequest::getTeamId]];
            }
            if (isset($requestData[MarketingGroupBuyTeamMemberQueryRequest::getUserId])) {
                $condition[] = [MarketingGroupBuyTeamMemberEntity::getUserId, '=', $requestData[MarketingGroupBuyTeamMemberQueryRequest::getUserId]];
            }

            $result = $this->marketingGroupBuyTeamMemberService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingGroupBuyTeamMemberResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingGroupBuyTeamMemberQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingGroupBuyTeamMember/store', summary: '新增拼团团员接口', security: [['bearerAuth' => []]], tags: ['拼团团员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamMemberCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamMemberResponse::class),
        ],
    ))]
    public function store(MarketingGroupBuyTeamMemberCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingGroupBuyTeamMemberEntity::from($requestData);

            if ($this->marketingGroupBuyTeamMemberService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingGroupBuyTeamMember/show', summary: '获取拼团团员详情接口', security: [['bearerAuth' => []]], tags: ['拼团团员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamMemberResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingGroupBuyTeamMember = $this->marketingGroupBuyTeamMemberService->getOneById($id);
            if (empty($marketingGroupBuyTeamMember)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingGroupBuyTeamMemberResponse::from($marketingGroupBuyTeamMember);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingGroupBuyTeamMember/update', summary: '更新拼团团员接口', security: [['bearerAuth' => []]], tags: ['拼团团员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamMemberUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamMemberResponse::class),
        ],
    ))]
    public function update(MarketingGroupBuyTeamMemberUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingGroupBuyTeamMember = $this->marketingGroupBuyTeamMemberService->getOneById($id);
            if (empty($marketingGroupBuyTeamMember)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingGroupBuyTeamMemberEntity::from($requestData);

            $this->marketingGroupBuyTeamMemberService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingGroupBuyTeamMember/destroy', summary: '删除拼团团员接口', security: [['bearerAuth' => []]], tags: ['拼团团员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingGroupBuyTeamMemberDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingGroupBuyTeamMemberDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingGroupBuyTeamMemberDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingGroupBuyTeamMemberService->removeByIds($requestData['ids'])) {
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
