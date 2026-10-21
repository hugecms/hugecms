<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingPromotionScopeEntity;
use App\Domains\Marketing\Requests\MarketingPromotionScope\MarketingPromotionScopeCreateRequest;
use App\Domains\Marketing\Requests\MarketingPromotionScope\MarketingPromotionScopeDestroyRequest;
use App\Domains\Marketing\Requests\MarketingPromotionScope\MarketingPromotionScopeQueryRequest;
use App\Domains\Marketing\Requests\MarketingPromotionScope\MarketingPromotionScopeUpdateRequest;
use App\Domains\Marketing\Responses\MarketingPromotionScope\MarketingPromotionScopeDestroyResponse;
use App\Domains\Marketing\Responses\MarketingPromotionScope\MarketingPromotionScopeQueryResponse;
use App\Domains\Marketing\Responses\MarketingPromotionScope\MarketingPromotionScopeResponse;
use App\Domains\Marketing\Services\MarketingPromotionScopeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingPromotionScopeController extends BaseController
{
    public function __construct(
        private readonly MarketingPromotionScopeService $marketingPromotionScopeService,
    ) {}

    #[OA\Post(path: '/marketingPromotionScope/search', summary: '查询促销适用范围列表接口', security: [['bearerAuth' => []]], tags: ['促销适用范围模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionScopeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionScopeQueryResponse::class),
        ],
    ))]
    public function search(MarketingPromotionScopeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getScopeType])) {
                $condition[] = [MarketingPromotionScopeEntity::getScopeType, '=', $requestData[MarketingPromotionScopeQueryRequest::getScopeType]];
            }
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getTargetId])) {
                $condition[] = [MarketingPromotionScopeEntity::getTargetId, '=', $requestData[MarketingPromotionScopeQueryRequest::getTargetId]];
            }
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getId])) {
                $condition[] = [MarketingPromotionScopeEntity::getId, '=', $requestData[MarketingPromotionScopeQueryRequest::getId]];
            }
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getPromotionId])) {
                $condition[] = [MarketingPromotionScopeEntity::getPromotionId, '=', $requestData[MarketingPromotionScopeQueryRequest::getPromotionId]];
            }
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getScopeType])) {
                $condition[] = [MarketingPromotionScopeEntity::getScopeType, '=', $requestData[MarketingPromotionScopeQueryRequest::getScopeType]];
            }
            if (isset($requestData[MarketingPromotionScopeQueryRequest::getTargetId])) {
                $condition[] = [MarketingPromotionScopeEntity::getTargetId, '=', $requestData[MarketingPromotionScopeQueryRequest::getTargetId]];
            }

            $result = $this->marketingPromotionScopeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingPromotionScopeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingPromotionScopeQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingPromotionScope/store', summary: '新增促销适用范围接口', security: [['bearerAuth' => []]], tags: ['促销适用范围模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionScopeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionScopeResponse::class),
        ],
    ))]
    public function store(MarketingPromotionScopeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingPromotionScopeEntity::from($requestData);

            if ($this->marketingPromotionScopeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingPromotionScope/show', summary: '获取促销适用范围详情接口', security: [['bearerAuth' => []]], tags: ['促销适用范围模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionScopeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingPromotionScope = $this->marketingPromotionScopeService->getOneById($id);
            if (empty($marketingPromotionScope)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingPromotionScopeResponse::from($marketingPromotionScope);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingPromotionScope/update', summary: '更新促销适用范围接口', security: [['bearerAuth' => []]], tags: ['促销适用范围模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionScopeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionScopeResponse::class),
        ],
    ))]
    public function update(MarketingPromotionScopeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingPromotionScope = $this->marketingPromotionScopeService->getOneById($id);
            if (empty($marketingPromotionScope)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingPromotionScopeEntity::from($requestData);

            $this->marketingPromotionScopeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingPromotionScope/destroy', summary: '删除促销适用范围接口', security: [['bearerAuth' => []]], tags: ['促销适用范围模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionScopeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionScopeDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingPromotionScopeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingPromotionScopeService->removeByIds($requestData['ids'])) {
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
