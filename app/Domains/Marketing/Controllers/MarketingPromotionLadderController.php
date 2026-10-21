<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingPromotionLadderEntity;
use App\Domains\Marketing\Requests\MarketingPromotionLadder\MarketingPromotionLadderCreateRequest;
use App\Domains\Marketing\Requests\MarketingPromotionLadder\MarketingPromotionLadderDestroyRequest;
use App\Domains\Marketing\Requests\MarketingPromotionLadder\MarketingPromotionLadderQueryRequest;
use App\Domains\Marketing\Requests\MarketingPromotionLadder\MarketingPromotionLadderUpdateRequest;
use App\Domains\Marketing\Responses\MarketingPromotionLadder\MarketingPromotionLadderDestroyResponse;
use App\Domains\Marketing\Responses\MarketingPromotionLadder\MarketingPromotionLadderQueryResponse;
use App\Domains\Marketing\Responses\MarketingPromotionLadder\MarketingPromotionLadderResponse;
use App\Domains\Marketing\Services\MarketingPromotionLadderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingPromotionLadderController extends BaseController
{
    public function __construct(
        private readonly MarketingPromotionLadderService $marketingPromotionLadderService,
    ) {}

    #[OA\Post(path: '/marketingPromotionLadder/search', summary: '查询促销满减阶梯列表接口', security: [['bearerAuth' => []]], tags: ['促销满减阶梯模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionLadderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionLadderQueryResponse::class),
        ],
    ))]
    public function search(MarketingPromotionLadderQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingPromotionLadderQueryRequest::getId])) {
                $condition[] = [MarketingPromotionLadderEntity::getId, '=', $requestData[MarketingPromotionLadderQueryRequest::getId]];
            }
            if (isset($requestData[MarketingPromotionLadderQueryRequest::getPromotionId])) {
                $condition[] = [MarketingPromotionLadderEntity::getPromotionId, '=', $requestData[MarketingPromotionLadderQueryRequest::getPromotionId]];
            }
            if (isset($requestData[MarketingPromotionLadderQueryRequest::getMinAmount])) {
                $condition[] = [MarketingPromotionLadderEntity::getMinAmount, '=', $requestData[MarketingPromotionLadderQueryRequest::getMinAmount]];
            }

            $result = $this->marketingPromotionLadderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingPromotionLadderResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingPromotionLadderQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingPromotionLadder/store', summary: '新增促销满减阶梯接口', security: [['bearerAuth' => []]], tags: ['促销满减阶梯模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionLadderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionLadderResponse::class),
        ],
    ))]
    public function store(MarketingPromotionLadderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingPromotionLadderEntity::from($requestData);

            if ($this->marketingPromotionLadderService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingPromotionLadder/show', summary: '获取促销满减阶梯详情接口', security: [['bearerAuth' => []]], tags: ['促销满减阶梯模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionLadderResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingPromotionLadder = $this->marketingPromotionLadderService->getOneById($id);
            if (empty($marketingPromotionLadder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingPromotionLadderResponse::from($marketingPromotionLadder);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingPromotionLadder/update', summary: '更新促销满减阶梯接口', security: [['bearerAuth' => []]], tags: ['促销满减阶梯模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionLadderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionLadderResponse::class),
        ],
    ))]
    public function update(MarketingPromotionLadderUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingPromotionLadder = $this->marketingPromotionLadderService->getOneById($id);
            if (empty($marketingPromotionLadder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingPromotionLadderEntity::from($requestData);

            $this->marketingPromotionLadderService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingPromotionLadder/destroy', summary: '删除促销满减阶梯接口', security: [['bearerAuth' => []]], tags: ['促销满减阶梯模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionLadderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionLadderDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingPromotionLadderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingPromotionLadderService->removeByIds($requestData['ids'])) {
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
