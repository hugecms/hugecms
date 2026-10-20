<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserSignInEntity;
use App\Domains\User\Requests\UserSignIn\UserSignInCreateRequest;
use App\Domains\User\Requests\UserSignIn\UserSignInDestroyRequest;
use App\Domains\User\Requests\UserSignIn\UserSignInQueryRequest;
use App\Domains\User\Requests\UserSignIn\UserSignInUpdateRequest;
use App\Domains\User\Responses\UserSignIn\UserSignInDestroyResponse;
use App\Domains\User\Responses\UserSignIn\UserSignInQueryResponse;
use App\Domains\User\Responses\UserSignIn\UserSignInResponse;
use App\Domains\User\Services\UserSignInService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserSignInController extends BaseController
{
    public function __construct(
        private readonly UserSignInService $userSignInService,
    ) {}

    #[OA\Post(path: '/userSignIn/search', summary: '查询用户签到列表接口', security: [['bearerAuth' => []]], tags: ['用户签到模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSignInQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSignInQueryResponse::class),
        ],
    ))]
    public function search(UserSignInQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserSignInQueryRequest::getSignDate])) {
                $condition[] = [UserSignInEntity::getSignDate, '=', $requestData[UserSignInQueryRequest::getSignDate]];
            }
            if (isset($requestData[UserSignInQueryRequest::getId])) {
                $condition[] = [UserSignInEntity::getId, '=', $requestData[UserSignInQueryRequest::getId]];
            }
            if (isset($requestData[UserSignInQueryRequest::getUserId])) {
                $condition[] = [UserSignInEntity::getUserId, '=', $requestData[UserSignInQueryRequest::getUserId]];
            }
            if (isset($requestData[UserSignInQueryRequest::getSignDate])) {
                $condition[] = [UserSignInEntity::getSignDate, '=', $requestData[UserSignInQueryRequest::getSignDate]];
            }

            $result = $this->userSignInService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserSignInResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserSignInQueryResponse::from($result);
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

    #[OA\Post(path: '/userSignIn/store', summary: '新增用户签到接口', security: [['bearerAuth' => []]], tags: ['用户签到模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSignInCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSignInResponse::class),
        ],
    ))]
    public function store(UserSignInCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserSignInEntity::from($requestData);

            if ($this->userSignInService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userSignIn/show', summary: '获取用户签到详情接口', security: [['bearerAuth' => []]], tags: ['用户签到模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSignInResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userSignIn = $this->userSignInService->getOneById($id);
            if (empty($userSignIn)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserSignInResponse::from($userSignIn);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userSignIn/update', summary: '更新用户签到接口', security: [['bearerAuth' => []]], tags: ['用户签到模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSignInUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSignInResponse::class),
        ],
    ))]
    public function update(UserSignInUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userSignIn = $this->userSignInService->getOneById($id);
            if (empty($userSignIn)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserSignInEntity::from($requestData);

            $this->userSignInService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userSignIn/destroy', summary: '删除用户签到接口', security: [['bearerAuth' => []]], tags: ['用户签到模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSignInDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSignInDestroyResponse::class),
        ],
    ))]
    public function destroy(UserSignInDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userSignInService->removeByIds($requestData['ids'])) {
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
