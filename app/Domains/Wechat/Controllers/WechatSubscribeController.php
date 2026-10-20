<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatSubscribeEntity;
use App\Domains\Wechat\Requests\WechatSubscribe\WechatSubscribeCreateRequest;
use App\Domains\Wechat\Requests\WechatSubscribe\WechatSubscribeDestroyRequest;
use App\Domains\Wechat\Requests\WechatSubscribe\WechatSubscribeQueryRequest;
use App\Domains\Wechat\Requests\WechatSubscribe\WechatSubscribeUpdateRequest;
use App\Domains\Wechat\Responses\WechatSubscribe\WechatSubscribeDestroyResponse;
use App\Domains\Wechat\Responses\WechatSubscribe\WechatSubscribeQueryResponse;
use App\Domains\Wechat\Responses\WechatSubscribe\WechatSubscribeResponse;
use App\Domains\Wechat\Services\WechatSubscribeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatSubscribeController extends BaseController
{
    public function __construct(
        private readonly WechatSubscribeService $wechatSubscribeService,
    ) {}

    #[OA\Post(path: '/wechatSubscribe/search', summary: '查询用户订阅授权列表接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscribeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscribeQueryResponse::class),
        ],
    ))]
    public function search(WechatSubscribeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatSubscribeQueryRequest::getId])) {
                $condition[] = [WechatSubscribeEntity::getId, '=', $requestData[WechatSubscribeQueryRequest::getId]];
            }
            if (isset($requestData[WechatSubscribeQueryRequest::getUserId])) {
                $condition[] = [WechatSubscribeEntity::getUserId, '=', $requestData[WechatSubscribeQueryRequest::getUserId]];
            }
            if (isset($requestData[WechatSubscribeQueryRequest::getAppid])) {
                $condition[] = [WechatSubscribeEntity::getAppid, '=', $requestData[WechatSubscribeQueryRequest::getAppid]];
            }
            if (isset($requestData[WechatSubscribeQueryRequest::getTemplateId])) {
                $condition[] = [WechatSubscribeEntity::getTemplateId, '=', $requestData[WechatSubscribeQueryRequest::getTemplateId]];
            }

            $result = $this->wechatSubscribeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatSubscribeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatSubscribeQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatSubscribe/store', summary: '新增用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscribeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscribeResponse::class),
        ],
    ))]
    public function store(WechatSubscribeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatSubscribeEntity::from($requestData);

            if ($this->wechatSubscribeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatSubscribe/show', summary: '获取用户订阅授权详情接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscribeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatSubscribe = $this->wechatSubscribeService->getOneById($id);
            if (empty($wechatSubscribe)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatSubscribeResponse::from($wechatSubscribe);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatSubscribe/update', summary: '更新用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscribeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscribeResponse::class),
        ],
    ))]
    public function update(WechatSubscribeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatSubscribe = $this->wechatSubscribeService->getOneById($id);
            if (empty($wechatSubscribe)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatSubscribeEntity::from($requestData);

            $this->wechatSubscribeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatSubscribe/destroy', summary: '删除用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscribeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscribeDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatSubscribeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatSubscribeService->removeByIds($requestData['ids'])) {
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
