<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserWithdrawOrderEntity;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderCreateRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderDestroyRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderQueryRequest;
use App\Domains\User\Requests\UserWithdrawOrder\UserWithdrawOrderUpdateRequest;
use App\Domains\User\Responses\UserWithdrawOrder\UserWithdrawOrderDestroyResponse;
use App\Domains\User\Responses\UserWithdrawOrder\UserWithdrawOrderQueryResponse;
use App\Domains\User\Responses\UserWithdrawOrder\UserWithdrawOrderResponse;
use App\Domains\User\Services\UserWithdrawOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserWithdrawOrderController extends BaseController
{
    public function __construct(
        private readonly UserWithdrawOrderService $userWithdrawOrderService,
    ) {}

    #[OA\Post(path: '/userWithdrawOrder/search', summary: '查询用户提现单列表接口', security: [['bearerAuth' => []]], tags: ['用户提现单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderQueryResponse::class),
        ],
    ))]
    public function search(UserWithdrawOrderQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserWithdrawOrderQueryRequest::getUserId])) {
                $condition[] = [UserWithdrawOrderEntity::getUserId, '=', $requestData[UserWithdrawOrderQueryRequest::getUserId]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getStatus])) {
                $condition[] = [UserWithdrawOrderEntity::getStatus, '=', $requestData[UserWithdrawOrderQueryRequest::getStatus]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getId])) {
                $condition[] = [UserWithdrawOrderEntity::getId, '=', $requestData[UserWithdrawOrderQueryRequest::getId]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getChannel])) {
                $condition[] = [UserWithdrawOrderEntity::getChannel, '=', $requestData[UserWithdrawOrderQueryRequest::getChannel]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getChannelOrderNo])) {
                $condition[] = [UserWithdrawOrderEntity::getChannelOrderNo, '=', $requestData[UserWithdrawOrderQueryRequest::getChannelOrderNo]];
            }
            if (isset($requestData[UserWithdrawOrderQueryRequest::getWithdrawNo])) {
                $condition[] = [UserWithdrawOrderEntity::getWithdrawNo, '=', $requestData[UserWithdrawOrderQueryRequest::getWithdrawNo]];
            }

            $result = $this->userWithdrawOrderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserWithdrawOrderResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserWithdrawOrderQueryResponse::from($result);
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

    #[OA\Post(path: '/userWithdrawOrder/store', summary: '新增用户提现单接口', security: [['bearerAuth' => []]], tags: ['用户提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderResponse::class),
        ],
    ))]
    public function store(UserWithdrawOrderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserWithdrawOrderEntity::from($requestData);

            if ($this->userWithdrawOrderService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userWithdrawOrder/show', summary: '获取用户提现单详情接口', security: [['bearerAuth' => []]], tags: ['用户提现单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userWithdrawOrder = $this->userWithdrawOrderService->getOneById($id);
            if (empty($userWithdrawOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserWithdrawOrderResponse::from($userWithdrawOrder);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userWithdrawOrder/update', summary: '更新用户提现单接口', security: [['bearerAuth' => []]], tags: ['用户提现单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderResponse::class),
        ],
    ))]
    public function update(UserWithdrawOrderUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userWithdrawOrder = $this->userWithdrawOrderService->getOneById($id);
            if (empty($userWithdrawOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserWithdrawOrderEntity::from($requestData);

            $this->userWithdrawOrderService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userWithdrawOrder/destroy', summary: '删除用户提现单接口', security: [['bearerAuth' => []]], tags: ['用户提现单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserWithdrawOrderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserWithdrawOrderDestroyResponse::class),
        ],
    ))]
    public function destroy(UserWithdrawOrderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userWithdrawOrderService->removeByIds($requestData['ids'])) {
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
