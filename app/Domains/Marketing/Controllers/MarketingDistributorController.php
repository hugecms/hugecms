<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingDistributorEntity;
use App\Domains\Marketing\Requests\MarketingDistributor\MarketingDistributorCreateRequest;
use App\Domains\Marketing\Requests\MarketingDistributor\MarketingDistributorDestroyRequest;
use App\Domains\Marketing\Requests\MarketingDistributor\MarketingDistributorQueryRequest;
use App\Domains\Marketing\Requests\MarketingDistributor\MarketingDistributorUpdateRequest;
use App\Domains\Marketing\Responses\MarketingDistributor\MarketingDistributorDestroyResponse;
use App\Domains\Marketing\Responses\MarketingDistributor\MarketingDistributorQueryResponse;
use App\Domains\Marketing\Responses\MarketingDistributor\MarketingDistributorResponse;
use App\Domains\Marketing\Services\MarketingDistributorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingDistributorController extends BaseController
{
    public function __construct(
        private readonly MarketingDistributorService $marketingDistributorService,
    ) {}

    #[OA\Post(path: '/marketingDistributor/search', summary: '查询分销员列表接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributorQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributorQueryResponse::class),
        ],
    ))]
    public function search(MarketingDistributorQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingDistributorQueryRequest::getInviterId])) {
                $condition[] = [MarketingDistributorEntity::getInviterId, '=', $requestData[MarketingDistributorQueryRequest::getInviterId]];
            }
            if (isset($requestData[MarketingDistributorQueryRequest::getId])) {
                $condition[] = [MarketingDistributorEntity::getId, '=', $requestData[MarketingDistributorQueryRequest::getId]];
            }
            if (isset($requestData[MarketingDistributorQueryRequest::getUserId])) {
                $condition[] = [MarketingDistributorEntity::getUserId, '=', $requestData[MarketingDistributorQueryRequest::getUserId]];
            }

            $result = $this->marketingDistributorService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingDistributorResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingDistributorQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributor/store', summary: '新增分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributorCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributorResponse::class),
        ],
    ))]
    public function store(MarketingDistributorCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingDistributorEntity::from($requestData);

            if ($this->marketingDistributorService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributor/show', summary: '获取分销员详情接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributorResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributor = $this->marketingDistributorService->getOneById($id);
            if (empty($marketingDistributor)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingDistributorResponse::from($marketingDistributor);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributor/update', summary: '更新分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributorUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributorResponse::class),
        ],
    ))]
    public function update(MarketingDistributorUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributor = $this->marketingDistributorService->getOneById($id);
            if (empty($marketingDistributor)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingDistributorEntity::from($requestData);

            $this->marketingDistributorService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributor/destroy', summary: '删除分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributorDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributorDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingDistributorDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributorService->removeByIds($requestData['ids'])) {
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
