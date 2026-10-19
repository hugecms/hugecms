<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserSmsCodeEntity;
use App\Domains\User\Services\UserSmsCodeService;
use App\Domains\User\Requests\UserSmsCode\UserSmsCodeCreateRequest;
use App\Domains\User\Requests\UserSmsCode\UserSmsCodeDestroyRequest;
use App\Domains\User\Requests\UserSmsCode\UserSmsCodeQueryRequest;
use App\Domains\User\Requests\UserSmsCode\UserSmsCodeUpdateRequest;
use App\Domains\User\Responses\UserSmsCode\UserSmsCodeDestroyResponse;
use App\Domains\User\Responses\UserSmsCode\UserSmsCodeQueryResponse;
use App\Domains\User\Responses\UserSmsCode\UserSmsCodeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserSmsCodeController extends BaseController
{
    public function __construct(
        private readonly UserSmsCodeService $userSmsCodeService,
    ) {}

    #[OA\Post(path: '/userSmsCode/search', summary: '查询短信验证码列表接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSmsCodeQueryResponse::class),
        ],
    ))]
    public function search(UserSmsCodeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserSmsCodeQueryRequest::getCreateTime])) {
                $condition[] = [UserSmsCodeEntity::getCreateTime, '=', $requestData[UserSmsCodeQueryRequest::getCreateTime]];
            }
            if (isset($requestData[UserSmsCodeQueryRequest::getIp])) {
                $condition[] = [UserSmsCodeEntity::getIp, '=', $requestData[UserSmsCodeQueryRequest::getIp]];
            }
            if (isset($requestData[UserSmsCodeQueryRequest::getScene])) {
                $condition[] = [UserSmsCodeEntity::getScene, '=', $requestData[UserSmsCodeQueryRequest::getScene]];
            }
            if (isset($requestData[UserSmsCodeQueryRequest::getId])) {
                $condition[] = [UserSmsCodeEntity::getId, '=', $requestData[UserSmsCodeQueryRequest::getId]];
            }
            
            $result = $this->userSmsCodeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserSmsCodeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserSmsCodeQueryResponse::from($result);
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

    #[OA\Post(path: '/userSmsCode/store', summary: '新增短信验证码接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSmsCodeResponse::class),
        ],
    ))]
    public function store(UserSmsCodeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserSmsCodeEntity::from($requestData);

            if ($this->userSmsCodeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userSmsCode/show', summary: '获取短信验证码详情接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSmsCodeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userSmsCode = $this->userSmsCodeService->getOneById($id);
            if (empty($userSmsCode)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserSmsCodeResponse::from($userSmsCode);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userSmsCode/update', summary: '更新短信验证码接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSmsCodeResponse::class),
        ],
    ))]
    public function update(UserSmsCodeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userSmsCode = $this->userSmsCodeService->getOneById($id);
            if (empty($userSmsCode)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserSmsCodeEntity::from($requestData);

            $this->userSmsCodeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userSmsCode/destroy', summary: '删除短信验证码接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserSmsCodeDestroyResponse::class),
        ],
    ))]
    public function destroy(UserSmsCodeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userSmsCodeService->removeByIds($requestData['ids'])) {
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
