<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatQrcodeEntity;
use App\Domains\Wechat\Requests\WechatQrcode\WechatQrcodeCreateRequest;
use App\Domains\Wechat\Requests\WechatQrcode\WechatQrcodeDestroyRequest;
use App\Domains\Wechat\Requests\WechatQrcode\WechatQrcodeQueryRequest;
use App\Domains\Wechat\Requests\WechatQrcode\WechatQrcodeUpdateRequest;
use App\Domains\Wechat\Responses\WechatQrcode\WechatQrcodeDestroyResponse;
use App\Domains\Wechat\Responses\WechatQrcode\WechatQrcodeQueryResponse;
use App\Domains\Wechat\Responses\WechatQrcode\WechatQrcodeResponse;
use App\Domains\Wechat\Services\WechatQrcodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatQrcodeController extends BaseController
{
    public function __construct(
        private readonly WechatQrcodeService $wechatQrcodeService,
    ) {}

    #[OA\Post(path: '/wechatQrcode/search', summary: '查询微信渠道二维码列表接口', security: [['bearerAuth' => []]], tags: ['微信渠道二维码模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatQrcodeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatQrcodeQueryResponse::class),
        ],
    ))]
    public function search(WechatQrcodeQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatQrcodeQueryRequest::getType])) {
                $condition[] = [WechatQrcodeEntity::getType, '=', $requestData[WechatQrcodeQueryRequest::getType]];
            }
            if (isset($requestData[WechatQrcodeQueryRequest::getTargetId])) {
                $condition[] = [WechatQrcodeEntity::getTargetId, '=', $requestData[WechatQrcodeQueryRequest::getTargetId]];
            }
            if (isset($requestData[WechatQrcodeQueryRequest::getId])) {
                $condition[] = [WechatQrcodeEntity::getId, '=', $requestData[WechatQrcodeQueryRequest::getId]];
            }
            if (isset($requestData[WechatQrcodeQueryRequest::getAppid])) {
                $condition[] = [WechatQrcodeEntity::getAppid, '=', $requestData[WechatQrcodeQueryRequest::getAppid]];
            }
            if (isset($requestData[WechatQrcodeQueryRequest::getSceneStr])) {
                $condition[] = [WechatQrcodeEntity::getSceneStr, '=', $requestData[WechatQrcodeQueryRequest::getSceneStr]];
            }

            $result = $this->wechatQrcodeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatQrcodeResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatQrcodeQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatQrcode/store', summary: '新增微信渠道二维码接口', security: [['bearerAuth' => []]], tags: ['微信渠道二维码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatQrcodeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatQrcodeResponse::class),
        ],
    ))]
    public function store(WechatQrcodeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatQrcodeEntity::from($requestData);

            if ($this->wechatQrcodeService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatQrcode/show', summary: '获取微信渠道二维码详情接口', security: [['bearerAuth' => []]], tags: ['微信渠道二维码模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatQrcodeResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatQrcode = $this->wechatQrcodeService->getOneById($id);
            if (empty($wechatQrcode)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatQrcodeResponse::from($wechatQrcode);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatQrcode/update', summary: '更新微信渠道二维码接口', security: [['bearerAuth' => []]], tags: ['微信渠道二维码模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatQrcodeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatQrcodeResponse::class),
        ],
    ))]
    public function update(WechatQrcodeUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatQrcode = $this->wechatQrcodeService->getOneById($id);
            if (empty($wechatQrcode)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatQrcodeEntity::from($requestData);

            $this->wechatQrcodeService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatQrcode/destroy', summary: '删除微信渠道二维码接口', security: [['bearerAuth' => []]], tags: ['微信渠道二维码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatQrcodeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatQrcodeDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatQrcodeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatQrcodeService->removeByIds($requestData['ids'])) {
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
