<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatUserEntity;
use App\Domains\Wechat\Services\WechatUserService;
use App\Domains\Wechat\Requests\WechatUser\WechatUserCreateRequest;
use App\Domains\Wechat\Requests\WechatUser\WechatUserDestroyRequest;
use App\Domains\Wechat\Requests\WechatUser\WechatUserQueryRequest;
use App\Domains\Wechat\Requests\WechatUser\WechatUserUpdateRequest;
use App\Domains\Wechat\Responses\WechatUser\WechatUserDestroyResponse;
use App\Domains\Wechat\Responses\WechatUser\WechatUserQueryResponse;
use App\Domains\Wechat\Responses\WechatUser\WechatUserResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatUserController extends BaseController
{
    public function __construct(
        private readonly WechatUserService $wechatUserService,
    ) {}

    #[OA\Post(path: '/wechatUser/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatUserQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatUserQueryResponse::class),
        ],
    ))]
    public function search(WechatUserQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatUserQueryRequest::getId])) {
                $condition[] = [WechatUserEntity::getId, '=', $requestData[WechatUserQueryRequest::getId]];
            }
            
            $result = $this->wechatUserService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatUserResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatUserQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatUser/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatUserCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatUserResponse::class),
        ],
    ))]
    public function store(WechatUserCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatUserEntity::from($requestData);

            if ($this->wechatUserService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatUser/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatUserResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatUser = $this->wechatUserService->getOneById($id);
            if (empty($wechatUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatUserResponse::from($wechatUser);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatUser/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatUserUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatUserResponse::class),
        ],
    ))]
    public function update(WechatUserUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatUser = $this->wechatUserService->getOneById($id);
            if (empty($wechatUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatUserEntity::from($requestData);

            $this->wechatUserService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatUser/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatUserDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatUserDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatUserDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatUserService->removeByIds($requestData['ids'])) {
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
