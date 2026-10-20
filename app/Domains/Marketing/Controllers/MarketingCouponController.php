<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingCouponEntity;
use App\Domains\Marketing\Requests\MarketingCoupon\MarketingCouponCreateRequest;
use App\Domains\Marketing\Requests\MarketingCoupon\MarketingCouponDestroyRequest;
use App\Domains\Marketing\Requests\MarketingCoupon\MarketingCouponQueryRequest;
use App\Domains\Marketing\Requests\MarketingCoupon\MarketingCouponUpdateRequest;
use App\Domains\Marketing\Responses\MarketingCoupon\MarketingCouponDestroyResponse;
use App\Domains\Marketing\Responses\MarketingCoupon\MarketingCouponQueryResponse;
use App\Domains\Marketing\Responses\MarketingCoupon\MarketingCouponResponse;
use App\Domains\Marketing\Services\MarketingCouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingCouponController extends BaseController
{
    public function __construct(
        private readonly MarketingCouponService $marketingCouponService,
    ) {}

    #[OA\Post(path: '/marketingCoupon/search', summary: '查询优惠券类型列表接口', security: [['bearerAuth' => []]], tags: ['优惠券类型模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCouponQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCouponQueryResponse::class),
        ],
    ))]
    public function search(MarketingCouponQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingCouponQueryRequest::getId])) {
                $condition[] = [MarketingCouponEntity::getId, '=', $requestData[MarketingCouponQueryRequest::getId]];
            }

            $result = $this->marketingCouponService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingCouponResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingCouponQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingCoupon/store', summary: '新增优惠券类型接口', security: [['bearerAuth' => []]], tags: ['优惠券类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCouponCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCouponResponse::class),
        ],
    ))]
    public function store(MarketingCouponCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingCouponEntity::from($requestData);

            if ($this->marketingCouponService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingCoupon/show', summary: '获取优惠券类型详情接口', security: [['bearerAuth' => []]], tags: ['优惠券类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCouponResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingCoupon = $this->marketingCouponService->getOneById($id);
            if (empty($marketingCoupon)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingCouponResponse::from($marketingCoupon);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingCoupon/update', summary: '更新优惠券类型接口', security: [['bearerAuth' => []]], tags: ['优惠券类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCouponUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCouponResponse::class),
        ],
    ))]
    public function update(MarketingCouponUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingCoupon = $this->marketingCouponService->getOneById($id);
            if (empty($marketingCoupon)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingCouponEntity::from($requestData);

            $this->marketingCouponService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingCoupon/destroy', summary: '删除优惠券类型接口', security: [['bearerAuth' => []]], tags: ['优惠券类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingCouponDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingCouponDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingCouponDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingCouponService->removeByIds($requestData['ids'])) {
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
