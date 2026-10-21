<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingBargainHelperEntity;
use App\Domains\Marketing\Requests\MarketingBargainHelper\MarketingBargainHelperCreateRequest;
use App\Domains\Marketing\Requests\MarketingBargainHelper\MarketingBargainHelperDestroyRequest;
use App\Domains\Marketing\Requests\MarketingBargainHelper\MarketingBargainHelperQueryRequest;
use App\Domains\Marketing\Requests\MarketingBargainHelper\MarketingBargainHelperUpdateRequest;
use App\Domains\Marketing\Responses\MarketingBargainHelper\MarketingBargainHelperDestroyResponse;
use App\Domains\Marketing\Responses\MarketingBargainHelper\MarketingBargainHelperQueryResponse;
use App\Domains\Marketing\Responses\MarketingBargainHelper\MarketingBargainHelperResponse;
use App\Domains\Marketing\Services\MarketingBargainHelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingBargainHelperController extends BaseController
{
    public function __construct(
        private readonly MarketingBargainHelperService $marketingBargainHelperService,
    ) {}

    #[OA\Post(path: '/marketingBargainHelper/search', summary: '查询帮砍记录列表接口', security: [['bearerAuth' => []]], tags: ['帮砍记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainHelperQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainHelperQueryResponse::class),
        ],
    ))]
    public function search(MarketingBargainHelperQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingBargainHelperQueryRequest::getHelperUserId])) {
                $condition[] = [MarketingBargainHelperEntity::getHelperUserId, '=', $requestData[MarketingBargainHelperQueryRequest::getHelperUserId]];
            }
            if (isset($requestData[MarketingBargainHelperQueryRequest::getId])) {
                $condition[] = [MarketingBargainHelperEntity::getId, '=', $requestData[MarketingBargainHelperQueryRequest::getId]];
            }
            if (isset($requestData[MarketingBargainHelperQueryRequest::getRecordId])) {
                $condition[] = [MarketingBargainHelperEntity::getRecordId, '=', $requestData[MarketingBargainHelperQueryRequest::getRecordId]];
            }
            if (isset($requestData[MarketingBargainHelperQueryRequest::getHelperUserId])) {
                $condition[] = [MarketingBargainHelperEntity::getHelperUserId, '=', $requestData[MarketingBargainHelperQueryRequest::getHelperUserId]];
            }

            $result = $this->marketingBargainHelperService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingBargainHelperResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingBargainHelperQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingBargainHelper/store', summary: '新增帮砍记录接口', security: [['bearerAuth' => []]], tags: ['帮砍记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainHelperCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainHelperResponse::class),
        ],
    ))]
    public function store(MarketingBargainHelperCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingBargainHelperEntity::from($requestData);

            if ($this->marketingBargainHelperService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingBargainHelper/show', summary: '获取帮砍记录详情接口', security: [['bearerAuth' => []]], tags: ['帮砍记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainHelperResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingBargainHelper = $this->marketingBargainHelperService->getOneById($id);
            if (empty($marketingBargainHelper)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingBargainHelperResponse::from($marketingBargainHelper);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingBargainHelper/update', summary: '更新帮砍记录接口', security: [['bearerAuth' => []]], tags: ['帮砍记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainHelperUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainHelperResponse::class),
        ],
    ))]
    public function update(MarketingBargainHelperUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingBargainHelper = $this->marketingBargainHelperService->getOneById($id);
            if (empty($marketingBargainHelper)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingBargainHelperEntity::from($requestData);

            $this->marketingBargainHelperService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingBargainHelper/destroy', summary: '删除帮砍记录接口', security: [['bearerAuth' => []]], tags: ['帮砍记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainHelperDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainHelperDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingBargainHelperDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingBargainHelperService->removeByIds($requestData['ids'])) {
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
