<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Marketing\Entities\MarketingDistributionUserEntity;
use App\Domains\Marketing\Requests\MarketingDistributionUser\MarketingDistributionUserCreateRequest;
use App\Domains\Marketing\Requests\MarketingDistributionUser\MarketingDistributionUserDestroyRequest;
use App\Domains\Marketing\Requests\MarketingDistributionUser\MarketingDistributionUserQueryRequest;
use App\Domains\Marketing\Requests\MarketingDistributionUser\MarketingDistributionUserUpdateRequest;
use App\Domains\Marketing\Responses\MarketingDistributionUser\MarketingDistributionUserDestroyResponse;
use App\Domains\Marketing\Responses\MarketingDistributionUser\MarketingDistributionUserQueryResponse;
use App\Domains\Marketing\Responses\MarketingDistributionUser\MarketingDistributionUserResponse;
use App\Domains\Marketing\Services\MarketingDistributionUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class MarketingDistributionUserController extends BaseController
{
    public function __construct(
        private readonly MarketingDistributionUserService $marketingDistributionUserService,
    ) {}

    #[OA\Post(path: '/marketingDistributionUser/search', summary: '查询分销员列表接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionUserQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionUserQueryResponse::class),
        ],
    ))]
    public function search(MarketingDistributionUserQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[MarketingDistributionUserQueryRequest::getInviterId])) {
                $condition[] = [MarketingDistributionUserEntity::getInviterId, '=', $requestData[MarketingDistributionUserQueryRequest::getInviterId]];
            }
            if (isset($requestData[MarketingDistributionUserQueryRequest::getId])) {
                $condition[] = [MarketingDistributionUserEntity::getId, '=', $requestData[MarketingDistributionUserQueryRequest::getId]];
            }
            if (isset($requestData[MarketingDistributionUserQueryRequest::getUserId])) {
                $condition[] = [MarketingDistributionUserEntity::getUserId, '=', $requestData[MarketingDistributionUserQueryRequest::getUserId]];
            }

            $result = $this->marketingDistributionUserService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = MarketingDistributionUserResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = MarketingDistributionUserQueryResponse::from($result);
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

    #[OA\Post(path: '/marketingDistributionUser/store', summary: '新增分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionUserCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionUserResponse::class),
        ],
    ))]
    public function store(MarketingDistributionUserCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = MarketingDistributionUserEntity::from($requestData);

            if ($this->marketingDistributionUserService->save($input->toEntity())) {
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

    #[OA\Get(path: '/marketingDistributionUser/show', summary: '获取分销员详情接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionUserResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $marketingDistributionUser = $this->marketingDistributionUserService->getOneById($id);
            if (empty($marketingDistributionUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = MarketingDistributionUserResponse::from($marketingDistributionUser);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/marketingDistributionUser/update', summary: '更新分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionUserUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionUserResponse::class),
        ],
    ))]
    public function update(MarketingDistributionUserUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $marketingDistributionUser = $this->marketingDistributionUserService->getOneById($id);
            if (empty($marketingDistributionUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = MarketingDistributionUserEntity::from($requestData);

            $this->marketingDistributionUserService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/marketingDistributionUser/destroy', summary: '删除分销员接口', security: [['bearerAuth' => []]], tags: ['分销员模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarketingDistributionUserDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: MarketingDistributionUserDestroyResponse::class),
        ],
    ))]
    public function destroy(MarketingDistributionUserDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->marketingDistributionUserService->removeByIds($requestData['ids'])) {
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
