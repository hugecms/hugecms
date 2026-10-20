<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingFlashSaleEntity;
use App\Domains\Marketing\Requests\MarketingFlashSale\MarketingFlashSaleCreateRequest;
use App\Domains\Marketing\Requests\MarketingFlashSale\MarketingFlashSaleDestroyRequest;
use App\Domains\Marketing\Requests\MarketingFlashSale\MarketingFlashSaleQueryRequest;
use App\Domains\Marketing\Requests\MarketingFlashSale\MarketingFlashSaleUpdateRequest;
use App\Domains\Marketing\Responses\MarketingFlashSale\MarketingFlashSaleDestroyResponse;
use App\Domains\Marketing\Responses\MarketingFlashSale\MarketingFlashSaleQueryResponse;
use App\Domains\Marketing\Responses\MarketingFlashSale\MarketingFlashSaleResponse;
use App\Domains\Marketing\Services\MarketingFlashSaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingFlashSaleController extends BaseController
{
    public function __construct(
        private readonly MarketingFlashSaleService $marketingFlashSaleService,
    ) {}

    #[OA\Post(path: '/marketingFlashSale/search', summary: '查询秒杀活�列表接口', security: [['bearerAuth' => []]], tags: ['秒杀活�模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleQueryResponse::class),
        ],
    ))]
    public function search(MarketingFlashSaleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingFlashSaleQueryRequest::getStatus])) {
                $condition[] = [MarketingFlashSaleEntity::getStatus, '=', $requestData[MarketingFlashSaleQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingFlashSaleQueryRequest::getStartTime])) {
                $condition[] = [MarketingFlashSaleEntity::getStartTime, '=', $requestData[MarketingFlashSaleQueryRequest::getStartTime]];
            }
            if (isset($requestData[MarketingFlashSaleQueryRequest::getEndTime])) {
                $condition[] = [MarketingFlashSaleEntity::getEndTime, '=', $requestData[MarketingFlashSaleQueryRequest::getEndTime]];
            }
            if (isset($requestData[MarketingFlashSaleQueryRequest::getId])) {
                $condition[] = [MarketingFlashSaleEntity::getId, '=', $requestData[MarketingFlashSaleQueryRequest::getId]];
            }

            $result = $this->marketingFlashSaleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingFlashSaleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingFlashSaleQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingFlashSale/store', summary: '新增秒杀活�接口', security: [['bearerAuth' => []]], tags: ['秒杀活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleResponse::class),
        ],
    ))]
    public function store(MarketingFlashSaleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingFlashSaleEntity::from($requestData);

            if ($this->marketingFlashSaleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingFlashSale/show', summary: '获取秒杀活�详情接口', security: [['bearerAuth' => []]], tags: ['秒杀活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingFlashSale = $this->marketingFlashSaleService->getOneById($id);
            if (empty($marketingFlashSale)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingFlashSaleResponse::from($marketingFlashSale);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingFlashSale/update', summary: '更新秒杀活�接口', security: [['bearerAuth' => []]], tags: ['秒杀活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleResponse::class),
        ],
    ))]
    public function update(MarketingFlashSaleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingFlashSale = $this->marketingFlashSaleService->getOneById($id);
            if (empty($marketingFlashSale)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingFlashSaleEntity::from($requestData);

            $this->marketingFlashSaleService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingFlashSale/destroy', summary: '删除秒杀活�接口', security: [['bearerAuth' => []]], tags: ['秒杀活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingFlashSaleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingFlashSaleDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingFlashSaleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingFlashSaleService->removeByIds($requestData['ids'])) {
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
