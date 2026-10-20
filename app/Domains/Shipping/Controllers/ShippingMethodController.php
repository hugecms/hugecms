<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Shipping\Entities\ShippingMethodEntity;
use App\Domains\Shipping\Requests\ShippingMethod\ShippingMethodCreateRequest;
use App\Domains\Shipping\Requests\ShippingMethod\ShippingMethodDestroyRequest;
use App\Domains\Shipping\Requests\ShippingMethod\ShippingMethodQueryRequest;
use App\Domains\Shipping\Requests\ShippingMethod\ShippingMethodUpdateRequest;
use App\Domains\Shipping\Responses\ShippingMethod\ShippingMethodDestroyResponse;
use App\Domains\Shipping\Responses\ShippingMethod\ShippingMethodQueryResponse;
use App\Domains\Shipping\Responses\ShippingMethod\ShippingMethodResponse;
use App\Domains\Shipping\Services\ShippingMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShippingMethodController extends BaseController
{
    public function __construct(
        private readonly ShippingMethodService $shippingMethodService,
    ) {}

    #[OA\Post(path: '/shippingMethod/search', summary: '查询配送方式列表接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingMethodQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingMethodQueryResponse::class),
        ],
    ))]
    public function search(ShippingMethodQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShippingMethodQueryRequest::getId])) {
                $condition[] = [ShippingMethodEntity::getId, '=', $requestData[ShippingMethodQueryRequest::getId]];
            }

            $result = $this->shippingMethodService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ShippingMethodResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ShippingMethodQueryResponse::from($result);
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

    #[OA\Post(path: '/shippingMethod/store', summary: '新增配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingMethodCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingMethodResponse::class),
        ],
    ))]
    public function store(ShippingMethodCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ShippingMethodEntity::from($requestData);

            if ($this->shippingMethodService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shippingMethod/show', summary: '获取配送方式详情接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingMethodResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $shippingMethod = $this->shippingMethodService->getOneById($id);
            if (empty($shippingMethod)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ShippingMethodResponse::from($shippingMethod);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shippingMethod/update', summary: '更新配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingMethodUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingMethodResponse::class),
        ],
    ))]
    public function update(ShippingMethodUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shippingMethod = $this->shippingMethodService->getOneById($id);
            if (empty($shippingMethod)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ShippingMethodEntity::from($requestData);

            $this->shippingMethodService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/shippingMethod/destroy', summary: '删除配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingMethodDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingMethodDestroyResponse::class),
        ],
    ))]
    public function destroy(ShippingMethodDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->shippingMethodService->removeByIds($requestData['ids'])) {
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
