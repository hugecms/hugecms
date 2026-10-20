<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatMessageEntity;
use App\Domains\Wechat\Requests\WechatMessage\WechatMessageCreateRequest;
use App\Domains\Wechat\Requests\WechatMessage\WechatMessageDestroyRequest;
use App\Domains\Wechat\Requests\WechatMessage\WechatMessageQueryRequest;
use App\Domains\Wechat\Requests\WechatMessage\WechatMessageUpdateRequest;
use App\Domains\Wechat\Responses\WechatMessage\WechatMessageDestroyResponse;
use App\Domains\Wechat\Responses\WechatMessage\WechatMessageQueryResponse;
use App\Domains\Wechat\Responses\WechatMessage\WechatMessageResponse;
use App\Domains\Wechat\Services\WechatMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatMessageController extends BaseController
{
    public function __construct(
        private readonly WechatMessageService $wechatMessageService,
    ) {}

    #[OA\Post(path: '/wechatMessage/search', summary: '查询微信消息推送记录列表接口', security: [['bearerAuth' => []]], tags: ['微信消息推送记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatMessageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatMessageQueryResponse::class),
        ],
    ))]
    public function search(WechatMessageQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatMessageQueryRequest::getTemplateId])) {
                $condition[] = [WechatMessageEntity::getTemplateId, '=', $requestData[WechatMessageQueryRequest::getTemplateId]];
            }
            if (isset($requestData[WechatMessageQueryRequest::getUserId])) {
                $condition[] = [WechatMessageEntity::getUserId, '=', $requestData[WechatMessageQueryRequest::getUserId]];
            }
            if (isset($requestData[WechatMessageQueryRequest::getStatus])) {
                $condition[] = [WechatMessageEntity::getStatus, '=', $requestData[WechatMessageQueryRequest::getStatus]];
            }
            if (isset($requestData[WechatMessageQueryRequest::getId])) {
                $condition[] = [WechatMessageEntity::getId, '=', $requestData[WechatMessageQueryRequest::getId]];
            }

            $result = $this->wechatMessageService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatMessageResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatMessageQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatMessage/store', summary: '新增微信消息推送记录接口', security: [['bearerAuth' => []]], tags: ['微信消息推送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatMessageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatMessageResponse::class),
        ],
    ))]
    public function store(WechatMessageCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatMessageEntity::from($requestData);

            if ($this->wechatMessageService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatMessage/show', summary: '获取微信消息推送记录详情接口', security: [['bearerAuth' => []]], tags: ['微信消息推送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatMessageResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatMessage = $this->wechatMessageService->getOneById($id);
            if (empty($wechatMessage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatMessageResponse::from($wechatMessage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatMessage/update', summary: '更新微信消息推送记录接口', security: [['bearerAuth' => []]], tags: ['微信消息推送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatMessageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatMessageResponse::class),
        ],
    ))]
    public function update(WechatMessageUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatMessage = $this->wechatMessageService->getOneById($id);
            if (empty($wechatMessage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatMessageEntity::from($requestData);

            $this->wechatMessageService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatMessage/destroy', summary: '删除微信消息推送记录接口', security: [['bearerAuth' => []]], tags: ['微信消息推送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatMessageDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatMessageDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatMessageDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatMessageService->removeByIds($requestData['ids'])) {
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
