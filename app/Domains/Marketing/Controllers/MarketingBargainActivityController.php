<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingBargainActivityEntity;
use App\Domains\Marketing\Requests\MarketingBargainActivity\MarketingBargainActivityCreateRequest;
use App\Domains\Marketing\Requests\MarketingBargainActivity\MarketingBargainActivityDestroyRequest;
use App\Domains\Marketing\Requests\MarketingBargainActivity\MarketingBargainActivityQueryRequest;
use App\Domains\Marketing\Requests\MarketingBargainActivity\MarketingBargainActivityUpdateRequest;
use App\Domains\Marketing\Responses\MarketingBargainActivity\MarketingBargainActivityDestroyResponse;
use App\Domains\Marketing\Responses\MarketingBargainActivity\MarketingBargainActivityQueryResponse;
use App\Domains\Marketing\Responses\MarketingBargainActivity\MarketingBargainActivityResponse;
use App\Domains\Marketing\Services\MarketingBargainActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingBargainActivityController extends BaseController
{
    public function __construct(
        private readonly MarketingBargainActivityService $marketingBargainActivityService,
    ) {}

    #[OA\Post(path: '/marketingBargainActivity/search', summary: '查询砍价活�列表接口', security: [['bearerAuth' => []]], tags: ['砍价活�模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainActivityQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainActivityQueryResponse::class),
        ],
    ))]
    public function search(MarketingBargainActivityQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingBargainActivityQueryRequest::getSpuId])) {
                $condition[] = [MarketingBargainActivityEntity::getSpuId, '=', $requestData[MarketingBargainActivityQueryRequest::getSpuId]];
            }
            if (isset($requestData[MarketingBargainActivityQueryRequest::getStatus])) {
                $condition[] = [MarketingBargainActivityEntity::getStatus, '=', $requestData[MarketingBargainActivityQueryRequest::getStatus]];
            }
            if (isset($requestData[MarketingBargainActivityQueryRequest::getId])) {
                $condition[] = [MarketingBargainActivityEntity::getId, '=', $requestData[MarketingBargainActivityQueryRequest::getId]];
            }

            $result = $this->marketingBargainActivityService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingBargainActivityResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingBargainActivityQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingBargainActivity/store', summary: '新增砍价活�接口', security: [['bearerAuth' => []]], tags: ['砍价活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainActivityCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainActivityResponse::class),
        ],
    ))]
    public function store(MarketingBargainActivityCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingBargainActivityEntity::from($requestData);

            if ($this->marketingBargainActivityService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingBargainActivity/show', summary: '获取砍价活�详情接口', security: [['bearerAuth' => []]], tags: ['砍价活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainActivityResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingBargainActivity = $this->marketingBargainActivityService->getOneById($id);
            if (empty($marketingBargainActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingBargainActivityResponse::from($marketingBargainActivity);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingBargainActivity/update', summary: '更新砍价活�接口', security: [['bearerAuth' => []]], tags: ['砍价活�模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainActivityResponse::class),
        ],
    ))]
    public function update(MarketingBargainActivityUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingBargainActivity = $this->marketingBargainActivityService->getOneById($id);
            if (empty($marketingBargainActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingBargainActivityEntity::from($requestData);

            $this->marketingBargainActivityService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingBargainActivity/destroy', summary: '删除砍价活�接口', security: [['bearerAuth' => []]], tags: ['砍价活�模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingBargainActivityDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingBargainActivityDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingBargainActivityDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingBargainActivityService->removeByIds($requestData['ids'])) {
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
