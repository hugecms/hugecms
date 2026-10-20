<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderCartEntity;
use App\Domains\Order\Requests\OrderCart\OrderCartCreateRequest;
use App\Domains\Order\Requests\OrderCart\OrderCartDestroyRequest;
use App\Domains\Order\Requests\OrderCart\OrderCartQueryRequest;
use App\Domains\Order\Requests\OrderCart\OrderCartUpdateRequest;
use App\Domains\Order\Responses\OrderCart\OrderCartDestroyResponse;
use App\Domains\Order\Responses\OrderCart\OrderCartQueryResponse;
use App\Domains\Order\Responses\OrderCart\OrderCartResponse;
use App\Domains\Order\Services\OrderCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderCartController extends BaseController
{
    public function __construct(
        private readonly OrderCartService $orderCartService,
    ) {}

    #[OA\Post(path: '/orderCart/search', summary: '查询购物车列表接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderCartQueryResponse::class),
        ],
    ))]
    public function search(OrderCartQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderCartQueryRequest::getProductId])) {
                $condition[] = [OrderCartEntity::getProductId, '=', $requestData[OrderCartQueryRequest::getProductId]];
            }
            if (isset($requestData[OrderCartQueryRequest::getId])) {
                $condition[] = [OrderCartEntity::getId, '=', $requestData[OrderCartQueryRequest::getId]];
            }
            if (isset($requestData[OrderCartQueryRequest::getUserId])) {
                $condition[] = [OrderCartEntity::getUserId, '=', $requestData[OrderCartQueryRequest::getUserId]];
            }
            if (isset($requestData[OrderCartQueryRequest::getProductId])) {
                $condition[] = [OrderCartEntity::getProductId, '=', $requestData[OrderCartQueryRequest::getProductId]];
            }
            if (isset($requestData[OrderCartQueryRequest::getSkuId])) {
                $condition[] = [OrderCartEntity::getSkuId, '=', $requestData[OrderCartQueryRequest::getSkuId]];
            }

            $result = $this->orderCartService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderCartResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderCartQueryResponse::from($result);
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

    #[OA\Post(path: '/orderCart/store', summary: '新增购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderCartResponse::class),
        ],
    ))]
    public function store(OrderCartCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderCartEntity::from($requestData);

            if ($this->orderCartService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderCart/show', summary: '获取购物车详情接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderCartResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderCart = $this->orderCartService->getOneById($id);
            if (empty($orderCart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderCartResponse::from($orderCart);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderCart/update', summary: '更新购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderCartResponse::class),
        ],
    ))]
    public function update(OrderCartUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderCart = $this->orderCartService->getOneById($id);
            if (empty($orderCart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderCartEntity::from($requestData);

            $this->orderCartService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderCart/destroy', summary: '删除购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderCartDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderCartDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderCartService->removeByIds($requestData['ids'])) {
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
