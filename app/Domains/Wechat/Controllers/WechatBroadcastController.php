<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatBroadcastEntity;
use App\Domains\Wechat\Requests\WechatBroadcast\WechatBroadcastCreateRequest;
use App\Domains\Wechat\Requests\WechatBroadcast\WechatBroadcastDestroyRequest;
use App\Domains\Wechat\Requests\WechatBroadcast\WechatBroadcastQueryRequest;
use App\Domains\Wechat\Requests\WechatBroadcast\WechatBroadcastUpdateRequest;
use App\Domains\Wechat\Responses\WechatBroadcast\WechatBroadcastDestroyResponse;
use App\Domains\Wechat\Responses\WechatBroadcast\WechatBroadcastQueryResponse;
use App\Domains\Wechat\Responses\WechatBroadcast\WechatBroadcastResponse;
use App\Domains\Wechat\Services\WechatBroadcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatBroadcastController extends BaseController
{
    public function __construct(
        private readonly WechatBroadcastService $wechatBroadcastService,
    ) {}

    #[OA\Post(path: '/wechatBroadcast/search', summary: '查询公众号群发记录列表接口', security: [['bearerAuth' => []]], tags: ['公众号群发记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatBroadcastQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatBroadcastQueryResponse::class),
        ],
    ))]
    public function search(WechatBroadcastQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatBroadcastQueryRequest::getId])) {
                $condition[] = [WechatBroadcastEntity::getId, '=', $requestData[WechatBroadcastQueryRequest::getId]];
            }

            $result = $this->wechatBroadcastService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatBroadcastResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatBroadcastQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatBroadcast/store', summary: '新增公众号群发记录接口', security: [['bearerAuth' => []]], tags: ['公众号群发记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatBroadcastCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatBroadcastResponse::class),
        ],
    ))]
    public function store(WechatBroadcastCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatBroadcastEntity::from($requestData);

            if ($this->wechatBroadcastService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatBroadcast/show', summary: '获取公众号群发记录详情接口', security: [['bearerAuth' => []]], tags: ['公众号群发记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatBroadcastResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatBroadcast = $this->wechatBroadcastService->getOneById($id);
            if (empty($wechatBroadcast)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatBroadcastResponse::from($wechatBroadcast);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatBroadcast/update', summary: '更新公众号群发记录接口', security: [['bearerAuth' => []]], tags: ['公众号群发记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatBroadcastUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatBroadcastResponse::class),
        ],
    ))]
    public function update(WechatBroadcastUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatBroadcast = $this->wechatBroadcastService->getOneById($id);
            if (empty($wechatBroadcast)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatBroadcastEntity::from($requestData);

            $this->wechatBroadcastService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatBroadcast/destroy', summary: '删除公众号群发记录接口', security: [['bearerAuth' => []]], tags: ['公众号群发记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatBroadcastDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatBroadcastDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatBroadcastDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatBroadcastService->removeByIds($requestData['ids'])) {
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
