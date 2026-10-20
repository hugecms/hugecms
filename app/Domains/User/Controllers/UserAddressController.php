<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserAddressEntity;
use App\Domains\User\Requests\UserAddress\UserAddressCreateRequest;
use App\Domains\User\Requests\UserAddress\UserAddressDestroyRequest;
use App\Domains\User\Requests\UserAddress\UserAddressQueryRequest;
use App\Domains\User\Requests\UserAddress\UserAddressUpdateRequest;
use App\Domains\User\Responses\UserAddress\UserAddressDestroyResponse;
use App\Domains\User\Responses\UserAddress\UserAddressQueryResponse;
use App\Domains\User\Responses\UserAddress\UserAddressResponse;
use App\Domains\User\Services\UserAddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAddressController extends BaseController
{
    public function __construct(
        private readonly UserAddressService $userAddressService,
    ) {}

    #[OA\Post(path: '/userAddress/search', summary: '查询用户收货地址列表接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAddressQueryResponse::class),
        ],
    ))]
    public function search(UserAddressQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAddressQueryRequest::getUserId])) {
                $condition[] = [UserAddressEntity::getUserId, '=', $requestData[UserAddressQueryRequest::getUserId]];
            }
            if (isset($requestData[UserAddressQueryRequest::getId])) {
                $condition[] = [UserAddressEntity::getId, '=', $requestData[UserAddressQueryRequest::getId]];
            }

            $result = $this->userAddressService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserAddressResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserAddressQueryResponse::from($result);
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

    #[OA\Post(path: '/userAddress/store', summary: '新增用户收货地址接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAddressResponse::class),
        ],
    ))]
    public function store(UserAddressCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserAddressEntity::from($requestData);

            if ($this->userAddressService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userAddress/show', summary: '获取用户收货地址详情接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAddressResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userAddress = $this->userAddressService->getOneById($id);
            if (empty($userAddress)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserAddressResponse::from($userAddress);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAddress/update', summary: '更新用户收货地址接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAddressResponse::class),
        ],
    ))]
    public function update(UserAddressUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAddress = $this->userAddressService->getOneById($id);
            if (empty($userAddress)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserAddressEntity::from($requestData);

            $this->userAddressService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userAddress/destroy', summary: '删除用户收货地址接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserAddressDestroyResponse::class),
        ],
    ))]
    public function destroy(UserAddressDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userAddressService->removeByIds($requestData['ids'])) {
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
