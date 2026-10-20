<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Shipping\Entities\ShippingShipmentTrackEntity;
use App\Domains\Shipping\Requests\ShippingShipmentTrack\ShippingShipmentTrackCreateRequest;
use App\Domains\Shipping\Requests\ShippingShipmentTrack\ShippingShipmentTrackDestroyRequest;
use App\Domains\Shipping\Requests\ShippingShipmentTrack\ShippingShipmentTrackQueryRequest;
use App\Domains\Shipping\Requests\ShippingShipmentTrack\ShippingShipmentTrackUpdateRequest;
use App\Domains\Shipping\Responses\ShippingShipmentTrack\ShippingShipmentTrackDestroyResponse;
use App\Domains\Shipping\Responses\ShippingShipmentTrack\ShippingShipmentTrackQueryResponse;
use App\Domains\Shipping\Responses\ShippingShipmentTrack\ShippingShipmentTrackResponse;
use App\Domains\Shipping\Services\ShippingShipmentTrackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShippingShipmentTrackController extends BaseController
{
    public function __construct(
        private readonly ShippingShipmentTrackService $shippingShipmentTrackService,
    ) {}

    #[OA\Post(path: '/shippingShipmentTrack/search', summary: '查询物流轨迹列表接口', security: [['bearerAuth' => []]], tags: ['物流轨迹模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingShipmentTrackQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingShipmentTrackQueryResponse::class),
        ],
    ))]
    public function search(ShippingShipmentTrackQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShippingShipmentTrackQueryRequest::getShipmentId])) {
                $condition[] = [ShippingShipmentTrackEntity::getShipmentId, '=', $requestData[ShippingShipmentTrackQueryRequest::getShipmentId]];
            }
            if (isset($requestData[ShippingShipmentTrackQueryRequest::getAcceptTime])) {
                $condition[] = [ShippingShipmentTrackEntity::getAcceptTime, '=', $requestData[ShippingShipmentTrackQueryRequest::getAcceptTime]];
            }
            if (isset($requestData[ShippingShipmentTrackQueryRequest::getId])) {
                $condition[] = [ShippingShipmentTrackEntity::getId, '=', $requestData[ShippingShipmentTrackQueryRequest::getId]];
            }

            $result = $this->shippingShipmentTrackService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ShippingShipmentTrackResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ShippingShipmentTrackQueryResponse::from($result);
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

    #[OA\Post(path: '/shippingShipmentTrack/store', summary: '新增物流轨迹接口', security: [['bearerAuth' => []]], tags: ['物流轨迹模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingShipmentTrackCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingShipmentTrackResponse::class),
        ],
    ))]
    public function store(ShippingShipmentTrackCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ShippingShipmentTrackEntity::from($requestData);

            if ($this->shippingShipmentTrackService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shippingShipmentTrack/show', summary: '获取物流轨迹详情接口', security: [['bearerAuth' => []]], tags: ['物流轨迹模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingShipmentTrackResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $shippingShipmentTrack = $this->shippingShipmentTrackService->getOneById($id);
            if (empty($shippingShipmentTrack)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ShippingShipmentTrackResponse::from($shippingShipmentTrack);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shippingShipmentTrack/update', summary: '更新物流轨迹接口', security: [['bearerAuth' => []]], tags: ['物流轨迹模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingShipmentTrackUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingShipmentTrackResponse::class),
        ],
    ))]
    public function update(ShippingShipmentTrackUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shippingShipmentTrack = $this->shippingShipmentTrackService->getOneById($id);
            if (empty($shippingShipmentTrack)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ShippingShipmentTrackEntity::from($requestData);

            $this->shippingShipmentTrackService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/shippingShipmentTrack/destroy', summary: '删除物流轨迹接口', security: [['bearerAuth' => []]], tags: ['物流轨迹模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingShipmentTrackDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShippingShipmentTrackDestroyResponse::class),
        ],
    ))]
    public function destroy(ShippingShipmentTrackDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->shippingShipmentTrackService->removeByIds($requestData['ids'])) {
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
