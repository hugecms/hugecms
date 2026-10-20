<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserRankEntity;
use App\Domains\User\Requests\UserRank\UserRankCreateRequest;
use App\Domains\User\Requests\UserRank\UserRankDestroyRequest;
use App\Domains\User\Requests\UserRank\UserRankQueryRequest;
use App\Domains\User\Requests\UserRank\UserRankUpdateRequest;
use App\Domains\User\Responses\UserRank\UserRankDestroyResponse;
use App\Domains\User\Responses\UserRank\UserRankQueryResponse;
use App\Domains\User\Responses\UserRank\UserRankResponse;
use App\Domains\User\Services\UserRankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserRankController extends BaseController
{
    public function __construct(
        private readonly UserRankService $userRankService,
    ) {}

    #[OA\Post(path: '/userRank/search', summary: '查询会员等级列表接口', security: [['bearerAuth' => []]], tags: ['会员等级模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRankQueryResponse::class),
        ],
    ))]
    public function search(UserRankQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserRankQueryRequest::getId])) {
                $condition[] = [UserRankEntity::getId, '=', $requestData[UserRankQueryRequest::getId]];
            }

            $result = $this->userRankService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserRankResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserRankQueryResponse::from($result);
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

    #[OA\Post(path: '/userRank/store', summary: '新增会员等级接口', security: [['bearerAuth' => []]], tags: ['会员等级模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRankResponse::class),
        ],
    ))]
    public function store(UserRankCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserRankEntity::from($requestData);

            if ($this->userRankService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userRank/show', summary: '获取会员等级详情接口', security: [['bearerAuth' => []]], tags: ['会员等级模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRankResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userRank = $this->userRankService->getOneById($id);
            if (empty($userRank)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserRankResponse::from($userRank);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userRank/update', summary: '更新会员等级接口', security: [['bearerAuth' => []]], tags: ['会员等级模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRankResponse::class),
        ],
    ))]
    public function update(UserRankUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userRank = $this->userRankService->getOneById($id);
            if (empty($userRank)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserRankEntity::from($requestData);

            $this->userRankService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userRank/destroy', summary: '删除会员等级接口', security: [['bearerAuth' => []]], tags: ['会员等级模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserRankDestroyResponse::class),
        ],
    ))]
    public function destroy(UserRankDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userRankService->removeByIds($requestData['ids'])) {
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
