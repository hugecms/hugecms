<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatFanEntity;
use App\Domains\Wechat\Requests\WechatFan\WechatFanCreateRequest;
use App\Domains\Wechat\Requests\WechatFan\WechatFanDestroyRequest;
use App\Domains\Wechat\Requests\WechatFan\WechatFanQueryRequest;
use App\Domains\Wechat\Requests\WechatFan\WechatFanUpdateRequest;
use App\Domains\Wechat\Responses\WechatFan\WechatFanDestroyResponse;
use App\Domains\Wechat\Responses\WechatFan\WechatFanQueryResponse;
use App\Domains\Wechat\Responses\WechatFan\WechatFanResponse;
use App\Domains\Wechat\Services\WechatFanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatFanController extends BaseController
{
    public function __construct(
        private readonly WechatFanService $wechatFanService,
    ) {}

    #[OA\Post(path: '/wechatFan/search', summary: '查询公众号粉丝列表接口', security: [['bearerAuth' => []]], tags: ['公众号粉丝模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatFanQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatFanQueryResponse::class),
        ],
    ))]
    public function search(WechatFanQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatFanQueryRequest::getUnionid])) {
                $condition[] = [WechatFanEntity::getUnionid, '=', $requestData[WechatFanQueryRequest::getUnionid]];
            }
            if (isset($requestData[WechatFanQueryRequest::getUserId])) {
                $condition[] = [WechatFanEntity::getUserId, '=', $requestData[WechatFanQueryRequest::getUserId]];
            }
            if (isset($requestData[WechatFanQueryRequest::getId])) {
                $condition[] = [WechatFanEntity::getId, '=', $requestData[WechatFanQueryRequest::getId]];
            }
            if (isset($requestData[WechatFanQueryRequest::getAppid])) {
                $condition[] = [WechatFanEntity::getAppid, '=', $requestData[WechatFanQueryRequest::getAppid]];
            }
            if (isset($requestData[WechatFanQueryRequest::getOpenid])) {
                $condition[] = [WechatFanEntity::getOpenid, '=', $requestData[WechatFanQueryRequest::getOpenid]];
            }

            $result = $this->wechatFanService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatFanResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatFanQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatFan/store', summary: '新增公众号粉丝接口', security: [['bearerAuth' => []]], tags: ['公众号粉丝模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatFanCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatFanResponse::class),
        ],
    ))]
    public function store(WechatFanCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatFanEntity::from($requestData);

            if ($this->wechatFanService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatFan/show', summary: '获取公众号粉丝详情接口', security: [['bearerAuth' => []]], tags: ['公众号粉丝模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatFanResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatFan = $this->wechatFanService->getOneById($id);
            if (empty($wechatFan)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatFanResponse::from($wechatFan);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatFan/update', summary: '更新公众号粉丝接口', security: [['bearerAuth' => []]], tags: ['公众号粉丝模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatFanUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatFanResponse::class),
        ],
    ))]
    public function update(WechatFanUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatFan = $this->wechatFanService->getOneById($id);
            if (empty($wechatFan)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatFanEntity::from($requestData);

            $this->wechatFanService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatFan/destroy', summary: '删除公众号粉丝接口', security: [['bearerAuth' => []]], tags: ['公众号粉丝模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatFanDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatFanDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatFanDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatFanService->removeByIds($requestData['ids'])) {
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
