<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\User\Entities\UserFundApplicationEntity;
use App\Domains\User\Requests\UserFundApplication\UserFundApplicationCreateRequest;
use App\Domains\User\Requests\UserFundApplication\UserFundApplicationDestroyRequest;
use App\Domains\User\Requests\UserFundApplication\UserFundApplicationQueryRequest;
use App\Domains\User\Requests\UserFundApplication\UserFundApplicationUpdateRequest;
use App\Domains\User\Responses\UserFundApplication\UserFundApplicationDestroyResponse;
use App\Domains\User\Responses\UserFundApplication\UserFundApplicationQueryResponse;
use App\Domains\User\Responses\UserFundApplication\UserFundApplicationResponse;
use App\Domains\User\Services\UserFundApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserFundApplicationController extends BaseController
{
    public function __construct(
        private readonly UserFundApplicationService $userFundApplicationService,
    ) {}

    #[OA\Post(path: '/userFundApplication/search', summary: '查询用户资金申请列表接口', security: [['bearerAuth' => []]], tags: ['用户资金申请模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFundApplicationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFundApplicationQueryResponse::class),
        ],
    ))]
    public function search(UserFundApplicationQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserFundApplicationQueryRequest::getUserId])) {
                $condition[] = [UserFundApplicationEntity::getUserId, '=', $requestData[UserFundApplicationQueryRequest::getUserId]];
            }
            if (isset($requestData[UserFundApplicationQueryRequest::getStatus])) {
                $condition[] = [UserFundApplicationEntity::getStatus, '=', $requestData[UserFundApplicationQueryRequest::getStatus]];
            }
            if (isset($requestData[UserFundApplicationQueryRequest::getId])) {
                $condition[] = [UserFundApplicationEntity::getId, '=', $requestData[UserFundApplicationQueryRequest::getId]];
            }

            $result = $this->userFundApplicationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = UserFundApplicationResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = UserFundApplicationQueryResponse::from($result);
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

    #[OA\Post(path: '/userFundApplication/store', summary: '新增用户资金申请接口', security: [['bearerAuth' => []]], tags: ['用户资金申请模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFundApplicationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFundApplicationResponse::class),
        ],
    ))]
    public function store(UserFundApplicationCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = UserFundApplicationEntity::from($requestData);

            if ($this->userFundApplicationService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userFundApplication/show', summary: '获取用户资金申请详情接口', security: [['bearerAuth' => []]], tags: ['用户资金申请模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFundApplicationResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $userFundApplication = $this->userFundApplicationService->getOneById($id);
            if (empty($userFundApplication)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = UserFundApplicationResponse::from($userFundApplication);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userFundApplication/update', summary: '更新用户资金申请接口', security: [['bearerAuth' => []]], tags: ['用户资金申请模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFundApplicationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFundApplicationResponse::class),
        ],
    ))]
    public function update(UserFundApplicationUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userFundApplication = $this->userFundApplicationService->getOneById($id);
            if (empty($userFundApplication)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = UserFundApplicationEntity::from($requestData);

            $this->userFundApplicationService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/userFundApplication/destroy', summary: '删除用户资金申请接口', security: [['bearerAuth' => []]], tags: ['用户资金申请模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFundApplicationDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: UserFundApplicationDestroyResponse::class),
        ],
    ))]
    public function destroy(UserFundApplicationDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->userFundApplicationService->removeByIds($requestData['ids'])) {
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
