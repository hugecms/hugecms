<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserProfileEntity;
use App\Domains\User\Requests\UserProfile\UserProfileCreateRequest;
use App\Domains\User\Requests\UserProfile\UserProfileDestroyRequest;
use App\Domains\User\Requests\UserProfile\UserProfileQueryRequest;
use App\Domains\User\Requests\UserProfile\UserProfileUpdateRequest;
use App\Domains\User\Responses\UserProfile\UserProfileDestroyResponse;
use App\Domains\User\Responses\UserProfile\UserProfileQueryResponse;
use App\Domains\User\Responses\UserProfile\UserProfileResponse;
use App\Domains\User\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserProfileController extends BaseController
{
    public function __construct(
        private readonly UserProfileService $userProfileService,
    ) {}

    #[OA\Post(path: '/userProfile/search', summary: '查询用户资料列表接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserProfileQueryResponse::class),
        ],
    ))]
    public function search(UserProfileQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserProfileQueryRequest::getUserId])) {
                $condition[] = [UserProfileEntity::getUserId, '=', $requestData[UserProfileQueryRequest::getUserId]];
            }

            $result = $this->userProfileService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserProfileResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserProfileQueryResponse::from($result);
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

    #[OA\Post(path: '/userProfile/store', summary: '新增用户资料接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserProfileResponse::class),
        ],
    ))]
    public function store(UserProfileCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserProfileEntity::from($requestData);

            if ($this->userProfileService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userProfile/show', summary: '获取用户资料详情接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserProfileResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userProfile = $this->userProfileService->getOneById($id);
            if (empty($userProfile)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserProfileResponse::from($userProfile);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userProfile/update', summary: '更新用户资料接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserProfileResponse::class),
        ],
    ))]
    public function update(UserProfileUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userProfile = $this->userProfileService->getOneById($id);
            if (empty($userProfile)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserProfileEntity::from($requestData);

            $this->userProfileService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userProfile/destroy', summary: '删除用户资料接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserProfileDestroyResponse::class),
        ],
    ))]
    public function destroy(UserProfileDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userProfileService->removeByIds($requestData['ids'])) {
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
