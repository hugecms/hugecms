<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingAssistRecordEntity;
use App\Domains\Marketing\Requests\MarketingAssistRecord\MarketingAssistRecordCreateRequest;
use App\Domains\Marketing\Requests\MarketingAssistRecord\MarketingAssistRecordDestroyRequest;
use App\Domains\Marketing\Requests\MarketingAssistRecord\MarketingAssistRecordQueryRequest;
use App\Domains\Marketing\Requests\MarketingAssistRecord\MarketingAssistRecordUpdateRequest;
use App\Domains\Marketing\Responses\MarketingAssistRecord\MarketingAssistRecordDestroyResponse;
use App\Domains\Marketing\Responses\MarketingAssistRecord\MarketingAssistRecordQueryResponse;
use App\Domains\Marketing\Responses\MarketingAssistRecord\MarketingAssistRecordResponse;
use App\Domains\Marketing\Services\MarketingAssistRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingAssistRecordController extends BaseController
{
    public function __construct(
        private readonly MarketingAssistRecordService $marketingAssistRecordService,
    ) {}

    #[OA\Post(path: '/marketingAssistRecord/search', summary: '查询助力参与记录列表接口', security: [['bearerAuth' => []]], tags: ['助力参与记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistRecordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistRecordQueryResponse::class),
        ],
    ))]
    public function search(MarketingAssistRecordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingAssistRecordQueryRequest::getUserId])) {
                $condition[] = [MarketingAssistRecordEntity::getUserId, '=', $requestData[MarketingAssistRecordQueryRequest::getUserId]];
            }
            if (isset($requestData[MarketingAssistRecordQueryRequest::getId])) {
                $condition[] = [MarketingAssistRecordEntity::getId, '=', $requestData[MarketingAssistRecordQueryRequest::getId]];
            }
            if (isset($requestData[MarketingAssistRecordQueryRequest::getActivityId])) {
                $condition[] = [MarketingAssistRecordEntity::getActivityId, '=', $requestData[MarketingAssistRecordQueryRequest::getActivityId]];
            }
            if (isset($requestData[MarketingAssistRecordQueryRequest::getUserId])) {
                $condition[] = [MarketingAssistRecordEntity::getUserId, '=', $requestData[MarketingAssistRecordQueryRequest::getUserId]];
            }

            $result = $this->marketingAssistRecordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingAssistRecordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingAssistRecordQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingAssistRecord/store', summary: '新增助力参与记录接口', security: [['bearerAuth' => []]], tags: ['助力参与记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistRecordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistRecordResponse::class),
        ],
    ))]
    public function store(MarketingAssistRecordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingAssistRecordEntity::from($requestData);

            if ($this->marketingAssistRecordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingAssistRecord/show', summary: '获取助力参与记录详情接口', security: [['bearerAuth' => []]], tags: ['助力参与记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistRecordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingAssistRecord = $this->marketingAssistRecordService->getOneById($id);
            if (empty($marketingAssistRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingAssistRecordResponse::from($marketingAssistRecord);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingAssistRecord/update', summary: '更新助力参与记录接口', security: [['bearerAuth' => []]], tags: ['助力参与记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistRecordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistRecordResponse::class),
        ],
    ))]
    public function update(MarketingAssistRecordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingAssistRecord = $this->marketingAssistRecordService->getOneById($id);
            if (empty($marketingAssistRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingAssistRecordEntity::from($requestData);

            $this->marketingAssistRecordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingAssistRecord/destroy', summary: '删除助力参与记录接口', security: [['bearerAuth' => []]], tags: ['助力参与记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistRecordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistRecordDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingAssistRecordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingAssistRecordService->removeByIds($requestData['ids'])) {
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
