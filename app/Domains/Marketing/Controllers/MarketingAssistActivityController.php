<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingAssistActivityEntity;
use App\Domains\Marketing\Requests\MarketingAssistActivity\MarketingAssistActivityCreateRequest;
use App\Domains\Marketing\Requests\MarketingAssistActivity\MarketingAssistActivityDestroyRequest;
use App\Domains\Marketing\Requests\MarketingAssistActivity\MarketingAssistActivityQueryRequest;
use App\Domains\Marketing\Requests\MarketingAssistActivity\MarketingAssistActivityUpdateRequest;
use App\Domains\Marketing\Responses\MarketingAssistActivity\MarketingAssistActivityDestroyResponse;
use App\Domains\Marketing\Responses\MarketingAssistActivity\MarketingAssistActivityQueryResponse;
use App\Domains\Marketing\Responses\MarketingAssistActivity\MarketingAssistActivityResponse;
use App\Domains\Marketing\Services\MarketingAssistActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingAssistActivityController extends BaseController
{
    public function __construct(
        private readonly MarketingAssistActivityService $marketingAssistActivityService,
    ) {}

    #[OA\Post(path: '/marketingAssistActivity/search', summary: '查询助力活�列表接口', security: [['bearerAuth' => []]], tags: ['助力活�模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistActivityQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistActivityQueryResponse::class),
        ],
    ))]
    public function search(MarketingAssistActivityQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingAssistActivityQueryRequest::getId])) {
                $condition[] = [MarketingAssistActivityEntity::getId, '=', $requestData[MarketingAssistActivityQueryRequest::getId]];
            }

            $result = $this->marketingAssistActivityService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingAssistActivityResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingAssistActivityQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingAssistActivity/store', summary: '新增助力活�接口', security: [['bearerAuth' => []]], tags: ['助力活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistActivityCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistActivityResponse::class),
        ],
    ))]
    public function store(MarketingAssistActivityCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingAssistActivityEntity::from($requestData);

            if ($this->marketingAssistActivityService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingAssistActivity/show', summary: '获取助力活�详情接口', security: [['bearerAuth' => []]], tags: ['助力活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistActivityResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingAssistActivity = $this->marketingAssistActivityService->getOneById($id);
            if (empty($marketingAssistActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingAssistActivityResponse::from($marketingAssistActivity);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingAssistActivity/update', summary: '更新助力活�接口', security: [['bearerAuth' => []]], tags: ['助力活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistActivityResponse::class),
        ],
    ))]
    public function update(MarketingAssistActivityUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingAssistActivity = $this->marketingAssistActivityService->getOneById($id);
            if (empty($marketingAssistActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingAssistActivityEntity::from($requestData);

            $this->marketingAssistActivityService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingAssistActivity/destroy', summary: '删除助力活�接口', security: [['bearerAuth' => []]], tags: ['助力活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingAssistActivityDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingAssistActivityDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingAssistActivityDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingAssistActivityService->removeByIds($requestData['ids'])) {
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
