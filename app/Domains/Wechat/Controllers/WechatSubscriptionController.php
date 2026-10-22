<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatSubscriptionEntity;
use App\Domains\Wechat\Requests\WechatSubscription\WechatSubscriptionCreateRequest;
use App\Domains\Wechat\Requests\WechatSubscription\WechatSubscriptionDestroyRequest;
use App\Domains\Wechat\Requests\WechatSubscription\WechatSubscriptionQueryRequest;
use App\Domains\Wechat\Requests\WechatSubscription\WechatSubscriptionUpdateRequest;
use App\Domains\Wechat\Responses\WechatSubscription\WechatSubscriptionDestroyResponse;
use App\Domains\Wechat\Responses\WechatSubscription\WechatSubscriptionQueryResponse;
use App\Domains\Wechat\Responses\WechatSubscription\WechatSubscriptionResponse;
use App\Domains\Wechat\Services\WechatSubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatSubscriptionController extends BaseController
{
    public function __construct(
        private readonly WechatSubscriptionService $wechatSubscriptionService,
    ) {}

    #[OA\Post(path: '/wechatSubscription/search', summary: '查询用户订阅授权列表接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscriptionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscriptionQueryResponse::class),
        ],
    ))]
    public function search(WechatSubscriptionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatSubscriptionQueryRequest::getId])) {
                $condition[] = [WechatSubscriptionEntity::getId, '=', $requestData[WechatSubscriptionQueryRequest::getId]];
            }
            if (isset($requestData[WechatSubscriptionQueryRequest::getUserId])) {
                $condition[] = [WechatSubscriptionEntity::getUserId, '=', $requestData[WechatSubscriptionQueryRequest::getUserId]];
            }
            if (isset($requestData[WechatSubscriptionQueryRequest::getAppid])) {
                $condition[] = [WechatSubscriptionEntity::getAppid, '=', $requestData[WechatSubscriptionQueryRequest::getAppid]];
            }
            if (isset($requestData[WechatSubscriptionQueryRequest::getTemplateId])) {
                $condition[] = [WechatSubscriptionEntity::getTemplateId, '=', $requestData[WechatSubscriptionQueryRequest::getTemplateId]];
            }

            $result = $this->wechatSubscriptionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatSubscriptionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatSubscriptionQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatSubscription/store', summary: '新增用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscriptionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscriptionResponse::class),
        ],
    ))]
    public function store(WechatSubscriptionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatSubscriptionEntity::from($requestData);

            if ($this->wechatSubscriptionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatSubscription/show', summary: '获取用户订阅授权详情接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscriptionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatSubscription = $this->wechatSubscriptionService->getOneById($id);
            if (empty($wechatSubscription)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatSubscriptionResponse::from($wechatSubscription);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatSubscription/update', summary: '更新用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscriptionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscriptionResponse::class),
        ],
    ))]
    public function update(WechatSubscriptionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatSubscription = $this->wechatSubscriptionService->getOneById($id);
            if (empty($wechatSubscription)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatSubscriptionEntity::from($requestData);

            $this->wechatSubscriptionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatSubscription/destroy', summary: '删除用户订阅授权接口', security: [['bearerAuth' => []]], tags: ['用户订阅授权模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatSubscriptionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatSubscriptionDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatSubscriptionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatSubscriptionService->removeByIds($requestData['ids'])) {
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
