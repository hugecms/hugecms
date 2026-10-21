<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingPromotionEntity;
use App\Domains\Marketing\Requests\MarketingPromotion\MarketingPromotionCreateRequest;
use App\Domains\Marketing\Requests\MarketingPromotion\MarketingPromotionDestroyRequest;
use App\Domains\Marketing\Requests\MarketingPromotion\MarketingPromotionQueryRequest;
use App\Domains\Marketing\Requests\MarketingPromotion\MarketingPromotionUpdateRequest;
use App\Domains\Marketing\Responses\MarketingPromotion\MarketingPromotionDestroyResponse;
use App\Domains\Marketing\Responses\MarketingPromotion\MarketingPromotionQueryResponse;
use App\Domains\Marketing\Responses\MarketingPromotion\MarketingPromotionResponse;
use App\Domains\Marketing\Services\MarketingPromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingPromotionController extends BaseController
{
    public function __construct(
        private readonly MarketingPromotionService $marketingPromotionService,
    ) {}

    #[OA\Post(path: '/marketingPromotion/search', summary: '查询促销活�列表接口', security: [['bearerAuth' => []]], tags: ['促销活�模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionQueryResponse::class),
        ],
    ))]
    public function search(MarketingPromotionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingPromotionQueryRequest::getStatus])) {
                $condition[] = [MarketingPromotionEntity::getStatus, '=', $requestData[MarketingPromotionQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingPromotionQueryRequest::getStartTime])) {
                $condition[] = [MarketingPromotionEntity::getStartTime, '=', $requestData[MarketingPromotionQueryRequest::getStartTime]];
            }
            if (isset($requestData[MarketingPromotionQueryRequest::getEndTime])) {
                $condition[] = [MarketingPromotionEntity::getEndTime, '=', $requestData[MarketingPromotionQueryRequest::getEndTime]];
            }
            if (isset($requestData[MarketingPromotionQueryRequest::getId])) {
                $condition[] = [MarketingPromotionEntity::getId, '=', $requestData[MarketingPromotionQueryRequest::getId]];
            }

            $result = $this->marketingPromotionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingPromotionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingPromotionQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingPromotion/store', summary: '新增促销活�接口', security: [['bearerAuth' => []]], tags: ['促销活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionResponse::class),
        ],
    ))]
    public function store(MarketingPromotionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingPromotionEntity::from($requestData);

            if ($this->marketingPromotionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingPromotion/show', summary: '获取促销活�详情接口', security: [['bearerAuth' => []]], tags: ['促销活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingPromotion = $this->marketingPromotionService->getOneById($id);
            if (empty($marketingPromotion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingPromotionResponse::from($marketingPromotion);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingPromotion/update', summary: '更新促销活�接口', security: [['bearerAuth' => []]], tags: ['促销活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionResponse::class),
        ],
    ))]
    public function update(MarketingPromotionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingPromotion = $this->marketingPromotionService->getOneById($id);
            if (empty($marketingPromotion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingPromotionEntity::from($requestData);

            $this->marketingPromotionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingPromotion/destroy', summary: '删除促销活�接口', security: [['bearerAuth' => []]], tags: ['促销活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingPromotionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingPromotionService->removeByIds($requestData['ids'])) {
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
