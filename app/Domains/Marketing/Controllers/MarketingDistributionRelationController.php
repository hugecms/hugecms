<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingDistributionRelationEntity;
use App\Domains\Marketing\Requests\MarketingDistributionRelation\MarketingDistributionRelationCreateRequest;
use App\Domains\Marketing\Requests\MarketingDistributionRelation\MarketingDistributionRelationDestroyRequest;
use App\Domains\Marketing\Requests\MarketingDistributionRelation\MarketingDistributionRelationQueryRequest;
use App\Domains\Marketing\Requests\MarketingDistributionRelation\MarketingDistributionRelationUpdateRequest;
use App\Domains\Marketing\Responses\MarketingDistributionRelation\MarketingDistributionRelationDestroyResponse;
use App\Domains\Marketing\Responses\MarketingDistributionRelation\MarketingDistributionRelationQueryResponse;
use App\Domains\Marketing\Responses\MarketingDistributionRelation\MarketingDistributionRelationResponse;
use App\Domains\Marketing\Services\MarketingDistributionRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingDistributionRelationController extends BaseController
{
    public function __construct(
        private readonly MarketingDistributionRelationService $marketingDistributionRelationService,
    ) {}

    #[OA\Post(path: '/marketingDistributionRelation/search', summary: '查询分销绑定关系列表接口', security: [['bearerAuth' => []]], tags: ['分销绑定关系模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionRelationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionRelationQueryResponse::class),
        ],
    ))]
    public function search(MarketingDistributionRelationQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingDistributionRelationQueryRequest::getDistributorId])) {
                $condition[] = [MarketingDistributionRelationEntity::getDistributorId, '=', $requestData[MarketingDistributionRelationQueryRequest::getDistributorId]];
            }
            if (isset($requestData[MarketingDistributionRelationQueryRequest::getId])) {
                $condition[] = [MarketingDistributionRelationEntity::getId, '=', $requestData[MarketingDistributionRelationQueryRequest::getId]];
            }
            if (isset($requestData[MarketingDistributionRelationQueryRequest::getUserId])) {
                $condition[] = [MarketingDistributionRelationEntity::getUserId, '=', $requestData[MarketingDistributionRelationQueryRequest::getUserId]];
            }
            if (isset($requestData[MarketingDistributionRelationQueryRequest::getDistributorId])) {
                $condition[] = [MarketingDistributionRelationEntity::getDistributorId, '=', $requestData[MarketingDistributionRelationQueryRequest::getDistributorId]];
            }

            $result = $this->marketingDistributionRelationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingDistributionRelationResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingDistributionRelationQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributionRelation/store', summary: '新增分销绑定关系接口', security: [['bearerAuth' => []]], tags: ['分销绑定关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionRelationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionRelationResponse::class),
        ],
    ))]
    public function store(MarketingDistributionRelationCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingDistributionRelationEntity::from($requestData);

            if ($this->marketingDistributionRelationService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributionRelation/show', summary: '获取分销绑定关系详情接口', security: [['bearerAuth' => []]], tags: ['分销绑定关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionRelationResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributionRelation = $this->marketingDistributionRelationService->getOneById($id);
            if (empty($marketingDistributionRelation)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingDistributionRelationResponse::from($marketingDistributionRelation);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributionRelation/update', summary: '更新分销绑定关系接口', security: [['bearerAuth' => []]], tags: ['分销绑定关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionRelationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionRelationResponse::class),
        ],
    ))]
    public function update(MarketingDistributionRelationUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributionRelation = $this->marketingDistributionRelationService->getOneById($id);
            if (empty($marketingDistributionRelation)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingDistributionRelationEntity::from($requestData);

            $this->marketingDistributionRelationService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributionRelation/destroy', summary: '删除分销绑定关系接口', security: [['bearerAuth' => []]], tags: ['分销绑定关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionRelationDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionRelationDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingDistributionRelationDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributionRelationService->removeByIds($requestData['ids'])) {
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
