<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserSocialEntity;
use App\Domains\User\Requests\UserSocial\UserSocialCreateRequest;
use App\Domains\User\Requests\UserSocial\UserSocialDestroyRequest;
use App\Domains\User\Requests\UserSocial\UserSocialQueryRequest;
use App\Domains\User\Requests\UserSocial\UserSocialUpdateRequest;
use App\Domains\User\Responses\UserSocial\UserSocialDestroyResponse;
use App\Domains\User\Responses\UserSocial\UserSocialQueryResponse;
use App\Domains\User\Responses\UserSocial\UserSocialResponse;
use App\Domains\User\Services\UserSocialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserSocialController extends BaseController
{
    public function __construct(
        private readonly UserSocialService $userSocialService,
    ) {}

    #[OA\Post(path: '/userSocial/search', summary: '查询社交登录绑定列表接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSocialQueryResponse::class),
        ],
    ))]
    public function search(UserSocialQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserSocialQueryRequest::getUserId])) {
                $condition[] = [UserSocialEntity::getUserId, '=', $requestData[UserSocialQueryRequest::getUserId]];
            }
            if (isset($requestData[UserSocialQueryRequest::getId])) {
                $condition[] = [UserSocialEntity::getId, '=', $requestData[UserSocialQueryRequest::getId]];
            }
            if (isset($requestData[UserSocialQueryRequest::getPlatform])) {
                $condition[] = [UserSocialEntity::getPlatform, '=', $requestData[UserSocialQueryRequest::getPlatform]];
            }
            if (isset($requestData[UserSocialQueryRequest::getOpenId])) {
                $condition[] = [UserSocialEntity::getOpenId, '=', $requestData[UserSocialQueryRequest::getOpenId]];
            }

            $result = $this->userSocialService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserSocialResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserSocialQueryResponse::from($result);
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

    #[OA\Post(path: '/userSocial/store', summary: '新增社交登录绑定接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSocialResponse::class),
        ],
    ))]
    public function store(UserSocialCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserSocialEntity::from($requestData);

            if ($this->userSocialService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userSocial/show', summary: '获取社交登录绑定详情接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSocialResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userSocial = $this->userSocialService->getOneById($id);
            if (empty($userSocial)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserSocialResponse::from($userSocial);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userSocial/update', summary: '更新社交登录绑定接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSocialResponse::class),
        ],
    ))]
    public function update(UserSocialUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userSocial = $this->userSocialService->getOneById($id);
            if (empty($userSocial)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserSocialEntity::from($requestData);

            $this->userSocialService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userSocial/destroy', summary: '删除社交登录绑定接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSocialDestroyResponse::class),
        ],
    ))]
    public function destroy(UserSocialDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userSocialService->removeByIds($requestData['ids'])) {
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
