<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingAssistHelperEntity;
use App\Domains\Marketing\Requests\MarketingAssistHelper\MarketingAssistHelperCreateRequest;
use App\Domains\Marketing\Requests\MarketingAssistHelper\MarketingAssistHelperDestroyRequest;
use App\Domains\Marketing\Requests\MarketingAssistHelper\MarketingAssistHelperQueryRequest;
use App\Domains\Marketing\Requests\MarketingAssistHelper\MarketingAssistHelperUpdateRequest;
use App\Domains\Marketing\Responses\MarketingAssistHelper\MarketingAssistHelperDestroyResponse;
use App\Domains\Marketing\Responses\MarketingAssistHelper\MarketingAssistHelperQueryResponse;
use App\Domains\Marketing\Responses\MarketingAssistHelper\MarketingAssistHelperResponse;
use App\Domains\Marketing\Services\MarketingAssistHelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingAssistHelperController extends BaseController
{
    public function __construct(
        private readonly MarketingAssistHelperService $marketingAssistHelperService,
    ) {}

    #[OA\Post(path: '/marketingAssistHelper/search', summary: '查询助力人记录列表接口', security: [['bearerAuth' => []]], tags: ['助力人记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistHelperQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistHelperQueryResponse::class),
        ],
    ))]
    public function search(MarketingAssistHelperQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingAssistHelperQueryRequest::getHelperUserId])) {
                $condition[] = [MarketingAssistHelperEntity::getHelperUserId, '=', $requestData[MarketingAssistHelperQueryRequest::getHelperUserId]];
            }
            if (isset($requestData[MarketingAssistHelperQueryRequest::getId])) {
                $condition[] = [MarketingAssistHelperEntity::getId, '=', $requestData[MarketingAssistHelperQueryRequest::getId]];
            }
            if (isset($requestData[MarketingAssistHelperQueryRequest::getRecordId])) {
                $condition[] = [MarketingAssistHelperEntity::getRecordId, '=', $requestData[MarketingAssistHelperQueryRequest::getRecordId]];
            }
            if (isset($requestData[MarketingAssistHelperQueryRequest::getHelperUserId])) {
                $condition[] = [MarketingAssistHelperEntity::getHelperUserId, '=', $requestData[MarketingAssistHelperQueryRequest::getHelperUserId]];
            }

            $result = $this->marketingAssistHelperService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingAssistHelperResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingAssistHelperQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingAssistHelper/store', summary: '新增助力人记录接口', security: [['bearerAuth' => []]], tags: ['助力人记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistHelperCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistHelperResponse::class),
        ],
    ))]
    public function store(MarketingAssistHelperCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingAssistHelperEntity::from($requestData);

            if ($this->marketingAssistHelperService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingAssistHelper/show', summary: '获取助力人记录详情接口', security: [['bearerAuth' => []]], tags: ['助力人记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistHelperResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingAssistHelper = $this->marketingAssistHelperService->getOneById($id);
            if (empty($marketingAssistHelper)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingAssistHelperResponse::from($marketingAssistHelper);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingAssistHelper/update', summary: '更新助力人记录接口', security: [['bearerAuth' => []]], tags: ['助力人记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistHelperUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistHelperResponse::class),
        ],
    ))]
    public function update(MarketingAssistHelperUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingAssistHelper = $this->marketingAssistHelperService->getOneById($id);
            if (empty($marketingAssistHelper)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingAssistHelperEntity::from($requestData);

            $this->marketingAssistHelperService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingAssistHelper/destroy', summary: '删除助力人记录接口', security: [['bearerAuth' => []]], tags: ['助力人记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistHelperDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistHelperDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingAssistHelperDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingAssistHelperService->removeByIds($requestData['ids'])) {
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
