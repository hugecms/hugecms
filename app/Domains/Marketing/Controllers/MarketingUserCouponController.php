<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingUserCouponEntity;
use App\Domains\Marketing\Requests\MarketingUserCoupon\MarketingUserCouponCreateRequest;
use App\Domains\Marketing\Requests\MarketingUserCoupon\MarketingUserCouponDestroyRequest;
use App\Domains\Marketing\Requests\MarketingUserCoupon\MarketingUserCouponQueryRequest;
use App\Domains\Marketing\Requests\MarketingUserCoupon\MarketingUserCouponUpdateRequest;
use App\Domains\Marketing\Responses\MarketingUserCoupon\MarketingUserCouponDestroyResponse;
use App\Domains\Marketing\Responses\MarketingUserCoupon\MarketingUserCouponQueryResponse;
use App\Domains\Marketing\Responses\MarketingUserCoupon\MarketingUserCouponResponse;
use App\Domains\Marketing\Services\MarketingUserCouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingUserCouponController extends BaseController
{
    public function __construct(
        private readonly MarketingUserCouponService $marketingUserCouponService,
    ) {}

    #[OA\Post(path: '/marketingUserCoupon/search', summary: '查询用户优惠券列表接口', security: [['bearerAuth' => []]], tags: ['用户优惠券模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingUserCouponQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingUserCouponQueryResponse::class),
        ],
    ))]
    public function search(MarketingUserCouponQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingUserCouponQueryRequest::getCouponId])) {
                $condition[] = [MarketingUserCouponEntity::getCouponId, '=', $requestData[MarketingUserCouponQueryRequest::getCouponId]];
            }
            if (isset($requestData[MarketingUserCouponQueryRequest::getUserId])) {
                $condition[] = [MarketingUserCouponEntity::getUserId, '=', $requestData[MarketingUserCouponQueryRequest::getUserId]];
            }
            if (isset($requestData[MarketingUserCouponQueryRequest::getStatus])) {
                $condition[] = [MarketingUserCouponEntity::getStatus, '=', $requestData[MarketingUserCouponQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingUserCouponQueryRequest::getId])) {
                $condition[] = [MarketingUserCouponEntity::getId, '=', $requestData[MarketingUserCouponQueryRequest::getId]];
            }

            $result = $this->marketingUserCouponService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingUserCouponResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingUserCouponQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingUserCoupon/store', summary: '新增用户优惠券接口', security: [['bearerAuth' => []]], tags: ['用户优惠券模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingUserCouponCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingUserCouponResponse::class),
        ],
    ))]
    public function store(MarketingUserCouponCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingUserCouponEntity::from($requestData);

            if ($this->marketingUserCouponService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingUserCoupon/show', summary: '获取用户优惠券详情接口', security: [['bearerAuth' => []]], tags: ['用户优惠券模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingUserCouponResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingUserCoupon = $this->marketingUserCouponService->getOneById($id);
            if (empty($marketingUserCoupon)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingUserCouponResponse::from($marketingUserCoupon);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingUserCoupon/update', summary: '更新用户优惠券接口', security: [['bearerAuth' => []]], tags: ['用户优惠券模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingUserCouponUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingUserCouponResponse::class),
        ],
    ))]
    public function update(MarketingUserCouponUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingUserCoupon = $this->marketingUserCouponService->getOneById($id);
            if (empty($marketingUserCoupon)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingUserCouponEntity::from($requestData);

            $this->marketingUserCouponService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingUserCoupon/destroy', summary: '删除用户优惠券接口', security: [['bearerAuth' => []]], tags: ['用户优惠券模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingUserCouponDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingUserCouponDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingUserCouponDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingUserCouponService->removeByIds($requestData['ids'])) {
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
