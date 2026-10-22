<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingShareRecordEntity;
use App\Domains\Marketing\Requests\MarketingShareRecord\MarketingShareRecordCreateRequest;
use App\Domains\Marketing\Requests\MarketingShareRecord\MarketingShareRecordDestroyRequest;
use App\Domains\Marketing\Requests\MarketingShareRecord\MarketingShareRecordQueryRequest;
use App\Domains\Marketing\Requests\MarketingShareRecord\MarketingShareRecordUpdateRequest;
use App\Domains\Marketing\Responses\MarketingShareRecord\MarketingShareRecordDestroyResponse;
use App\Domains\Marketing\Responses\MarketingShareRecord\MarketingShareRecordQueryResponse;
use App\Domains\Marketing\Responses\MarketingShareRecord\MarketingShareRecordResponse;
use App\Domains\Marketing\Services\MarketingShareRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingShareRecordController extends BaseController
{
    public function __construct(
        private readonly MarketingShareRecordService $marketingShareRecordService,
    ) {}

    #[OA\Post(path: '/marketingShareRecord/search', summary: '查询分享行为记录列表接口', security: [['bearerAuth' => []]], tags: ['分享行为记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingShareRecordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingShareRecordQueryResponse::class),
        ],
    ))]
    public function search(MarketingShareRecordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingShareRecordQueryRequest::getSharerUserId])) {
                $condition[] = [MarketingShareRecordEntity::getSharerUserId, '=', $requestData[MarketingShareRecordQueryRequest::getSharerUserId]];
            }
            if (isset($requestData[MarketingShareRecordQueryRequest::getCreateTime])) {
                $condition[] = [MarketingShareRecordEntity::getCreateTime, '=', $requestData[MarketingShareRecordQueryRequest::getCreateTime]];
            }
            if (isset($requestData[MarketingShareRecordQueryRequest::getSpuId])) {
                $condition[] = [MarketingShareRecordEntity::getSpuId, '=', $requestData[MarketingShareRecordQueryRequest::getSpuId]];
            }
            if (isset($requestData[MarketingShareRecordQueryRequest::getCreateTime])) {
                $condition[] = [MarketingShareRecordEntity::getCreateTime, '=', $requestData[MarketingShareRecordQueryRequest::getCreateTime]];
            }
            if (isset($requestData[MarketingShareRecordQueryRequest::getId])) {
                $condition[] = [MarketingShareRecordEntity::getId, '=', $requestData[MarketingShareRecordQueryRequest::getId]];
            }

            $result = $this->marketingShareRecordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingShareRecordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingShareRecordQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingShareRecord/store', summary: '新增分享行为记录接口', security: [['bearerAuth' => []]], tags: ['分享行为记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingShareRecordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingShareRecordResponse::class),
        ],
    ))]
    public function store(MarketingShareRecordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingShareRecordEntity::from($requestData);

            if ($this->marketingShareRecordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingShareRecord/show', summary: '获取分享行为记录详情接口', security: [['bearerAuth' => []]], tags: ['分享行为记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingShareRecordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingShareRecord = $this->marketingShareRecordService->getOneById($id);
            if (empty($marketingShareRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingShareRecordResponse::from($marketingShareRecord);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingShareRecord/update', summary: '更新分享行为记录接口', security: [['bearerAuth' => []]], tags: ['分享行为记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingShareRecordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingShareRecordResponse::class),
        ],
    ))]
    public function update(MarketingShareRecordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingShareRecord = $this->marketingShareRecordService->getOneById($id);
            if (empty($marketingShareRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingShareRecordEntity::from($requestData);

            $this->marketingShareRecordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingShareRecord/destroy', summary: '删除分享行为记录接口', security: [['bearerAuth' => []]], tags: ['分享行为记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingShareRecordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingShareRecordDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingShareRecordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingShareRecordService->removeByIds($requestData['ids'])) {
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
