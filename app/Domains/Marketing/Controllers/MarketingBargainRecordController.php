<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingBargainRecordEntity;
use App\Domains\Marketing\Requests\MarketingBargainRecord\MarketingBargainRecordCreateRequest;
use App\Domains\Marketing\Requests\MarketingBargainRecord\MarketingBargainRecordDestroyRequest;
use App\Domains\Marketing\Requests\MarketingBargainRecord\MarketingBargainRecordQueryRequest;
use App\Domains\Marketing\Requests\MarketingBargainRecord\MarketingBargainRecordUpdateRequest;
use App\Domains\Marketing\Responses\MarketingBargainRecord\MarketingBargainRecordDestroyResponse;
use App\Domains\Marketing\Responses\MarketingBargainRecord\MarketingBargainRecordQueryResponse;
use App\Domains\Marketing\Responses\MarketingBargainRecord\MarketingBargainRecordResponse;
use App\Domains\Marketing\Services\MarketingBargainRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingBargainRecordController extends BaseController
{
    public function __construct(
        private readonly MarketingBargainRecordService $marketingBargainRecordService,
    ) {}

    #[OA\Post(path: '/marketingBargainRecord/search', summary: '查询砍价单列表接口', security: [['bearerAuth' => []]], tags: ['砍价单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainRecordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainRecordQueryResponse::class),
        ],
    ))]
    public function search(MarketingBargainRecordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingBargainRecordQueryRequest::getStatus])) {
                $condition[] = [MarketingBargainRecordEntity::getStatus, '=', $requestData[MarketingBargainRecordQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getExpireTime])) {
                $condition[] = [MarketingBargainRecordEntity::getExpireTime, '=', $requestData[MarketingBargainRecordQueryRequest::getExpireTime]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getUserId])) {
                $condition[] = [MarketingBargainRecordEntity::getUserId, '=', $requestData[MarketingBargainRecordQueryRequest::getUserId]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getStatus])) {
                $condition[] = [MarketingBargainRecordEntity::getStatus, '=', $requestData[MarketingBargainRecordQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getId])) {
                $condition[] = [MarketingBargainRecordEntity::getId, '=', $requestData[MarketingBargainRecordQueryRequest::getId]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getBargainNo])) {
                $condition[] = [MarketingBargainRecordEntity::getBargainNo, '=', $requestData[MarketingBargainRecordQueryRequest::getBargainNo]];
            }
            if (isset($requestData[MarketingBargainRecordQueryRequest::getOrderNo])) {
                $condition[] = [MarketingBargainRecordEntity::getOrderNo, '=', $requestData[MarketingBargainRecordQueryRequest::getOrderNo]];
            }

            $result = $this->marketingBargainRecordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingBargainRecordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingBargainRecordQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingBargainRecord/store', summary: '新增砍价单接口', security: [['bearerAuth' => []]], tags: ['砍价单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainRecordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainRecordResponse::class),
        ],
    ))]
    public function store(MarketingBargainRecordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingBargainRecordEntity::from($requestData);

            if ($this->marketingBargainRecordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingBargainRecord/show', summary: '获取砍价单详情接口', security: [['bearerAuth' => []]], tags: ['砍价单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainRecordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingBargainRecord = $this->marketingBargainRecordService->getOneById($id);
            if (empty($marketingBargainRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingBargainRecordResponse::from($marketingBargainRecord);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingBargainRecord/update', summary: '更新砍价单接口', security: [['bearerAuth' => []]], tags: ['砍价单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainRecordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainRecordResponse::class),
        ],
    ))]
    public function update(MarketingBargainRecordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingBargainRecord = $this->marketingBargainRecordService->getOneById($id);
            if (empty($marketingBargainRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingBargainRecordEntity::from($requestData);

            $this->marketingBargainRecordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingBargainRecord/destroy', summary: '删除砍价单接口', security: [['bearerAuth' => []]], tags: ['砍价单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainRecordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainRecordDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingBargainRecordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingBargainRecordService->removeByIds($requestData['ids'])) {
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
