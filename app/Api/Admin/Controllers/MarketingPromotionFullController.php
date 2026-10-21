<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\MarketingPromotionFull\MarketingPromotionFullCreateRequest;
use App\Api\Admin\Requests\MarketingPromotionFull\MarketingPromotionFullDestroyRequest;
use App\Api\Admin\Requests\MarketingPromotionFull\MarketingPromotionFullQueryRequest;
use App\Api\Admin\Requests\MarketingPromotionFull\MarketingPromotionFullUpdateRequest;
use App\Api\Admin\Responses\MarketingPromotionFull\MarketingPromotionFullDestroyResponse;
use App\Api\Admin\Responses\MarketingPromotionFull\MarketingPromotionFullResponse;
use App\Services\MarketingPromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

/**
 * 促销活动主子表业务端点：创建/更新整体提交范围(scopes)与优惠档位(ladders)，事务整体替换
 * 与 Domains 领域控制器的模板单表 CRUD 并存，路径区分 marketingPromotionFull/*（路由由 gen:route 生成）
 * 业务联动校验在 MarketingPromotionService::validate（类型与档位互斥、范围一致性、目标/门槛去重）
 */
class MarketingPromotionFullController extends BaseController
{
    public function __construct(
        private readonly MarketingPromotionService $marketingPromotionService,
    ) {}

    #[OA\Post(path: '/marketingPromotionFull/create', summary: '创建促销活动(含范围与档位)', security: [['bearerAuth' => []]], tags: ['促销活动(主子表)'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionFullCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionFullResponse::class),
        ],
    ))]
    public function create(MarketingPromotionFullCreateRequest $createRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            $promotionId = $this->marketingPromotionService->savePromotion(
                $this->buildEntity($createRequest->post()),
                $this->extractScopes($createRequest->post()),
                $createRequest->input(MarketingPromotionFullCreateRequest::getLadders, []),
            );

            DB::commit();

            return $this->success($this->detailToArray($promotionId));
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Post(path: '/marketingPromotionFull/update', summary: '更新促销活动(整体替换范围与档位)', security: [['bearerAuth' => []]], tags: ['促销活动(主子表)'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionFullUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionFullResponse::class),
        ],
    ))]
    public function update(MarketingPromotionFullUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query(MarketingPromotionFullUpdateRequest::getId, '0'));

        DB::beginTransaction();
        try {
            $this->marketingPromotionService->updatePromotion(
                $this->buildEntity($updateRequest->post()),
                $id,
                $this->extractScopes($updateRequest->post()),
                $updateRequest->input(MarketingPromotionFullUpdateRequest::getLadders, []),
            );

            DB::commit();

            return $this->success($this->detailToArray($id));
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Get(path: '/marketingPromotionFull/show', summary: '促销活动详情(含范围与档位)', security: [['bearerAuth' => []]], tags: ['促销活动(主子表)'])]
    #[OA\Parameter(name: 'id', description: '促销活动ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionFullResponse::class),
        ],
    ))]
    public function show(MarketingPromotionFullQueryRequest $queryRequest): JsonResponse
    {
        try {
            $id = \intval($queryRequest->query(MarketingPromotionFullQueryRequest::getId, '0'));

            return $this->success($this->detailToArray($id));
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/marketingPromotionFull/destroy', summary: '删除促销活动(级联清理范围与档位)', security: [['bearerAuth' => []]], tags: ['促销活动(主子表)'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingPromotionFullDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingPromotionFullDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingPromotionFullDestroyRequest $destroyRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            if ($this->marketingPromotionService->removeByIds(array_map('intval', $destroyRequest->input(MarketingPromotionFullDestroyRequest::getIds, [])))) {
                DB::commit();

                $destroyResponse = new MarketingPromotionFullDestroyResponse;
                $destroyResponse->setStatus(1);

                return $this->success($destroyResponse->toArray());
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

    /**
     * 请求体(camelCase) 映射为父表实体行(snake_case)
     */
    private function buildEntity(array $requestData): array
    {
        return [
            'name' => (string) ($requestData[MarketingPromotionFullCreateRequest::getName] ?? ''),
            'type' => (string) ($requestData[MarketingPromotionFullCreateRequest::getType] ?? ''),
            'range_type' => (string) ($requestData[MarketingPromotionFullCreateRequest::getRangeType] ?? 'all'),
            'start_time' => (string) ($requestData[MarketingPromotionFullCreateRequest::getStartTime] ?? ''),
            'end_time' => (string) ($requestData[MarketingPromotionFullCreateRequest::getEndTime] ?? ''),
            'status' => \intval($requestData[MarketingPromotionFullCreateRequest::getStatus] ?? 1),
        ];
    }

    /**
     * 提取范围子表（rangeType=all 时为空）
     */
    private function extractScopes(array $requestData): array
    {
        $scopes = $requestData[MarketingPromotionFullCreateRequest::getScopes] ?? [];

        return is_array($scopes) ? $scopes : [];
    }

    /**
     * 详情转响应数组（父表经 Entity 转 camelCase，子表由 Service 提供 camelCase 数组）
     */
    private function detailToArray(int $id): array
    {
        $detail = $this->marketingPromotionService->getDetailById($id);
        if ($detail === []) {
            throw new BusinessException(BusinessEnum::NOT_FOUND);
        }

        return MarketingPromotionFullResponse::from($detail)->toArray();
    }
}
