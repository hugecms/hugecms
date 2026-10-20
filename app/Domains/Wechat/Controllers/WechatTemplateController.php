<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Wechat\Entities\WechatTemplateEntity;
use App\Domains\Wechat\Requests\WechatTemplate\WechatTemplateCreateRequest;
use App\Domains\Wechat\Requests\WechatTemplate\WechatTemplateDestroyRequest;
use App\Domains\Wechat\Requests\WechatTemplate\WechatTemplateQueryRequest;
use App\Domains\Wechat\Requests\WechatTemplate\WechatTemplateUpdateRequest;
use App\Domains\Wechat\Responses\WechatTemplate\WechatTemplateDestroyResponse;
use App\Domains\Wechat\Responses\WechatTemplate\WechatTemplateQueryResponse;
use App\Domains\Wechat\Responses\WechatTemplate\WechatTemplateResponse;
use App\Domains\Wechat\Services\WechatTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WechatTemplateController extends BaseController
{
    public function __construct(
        private readonly WechatTemplateService $wechatTemplateService,
    ) {}

    #[OA\Post(path: '/wechatTemplate/search', summary: '查询微信消息模板列表接口', security: [['bearerAuth' => []]], tags: ['微信消息模板模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatTemplateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatTemplateQueryResponse::class),
        ],
    ))]
    public function search(WechatTemplateQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[WechatTemplateQueryRequest::getId])) {
                $condition[] = [WechatTemplateEntity::getId, '=', $requestData[WechatTemplateQueryRequest::getId]];
            }
            if (isset($requestData[WechatTemplateQueryRequest::getAppid])) {
                $condition[] = [WechatTemplateEntity::getAppid, '=', $requestData[WechatTemplateQueryRequest::getAppid]];
            }
            if (isset($requestData[WechatTemplateQueryRequest::getScene])) {
                $condition[] = [WechatTemplateEntity::getScene, '=', $requestData[WechatTemplateQueryRequest::getScene]];
            }
            if (isset($requestData[WechatTemplateQueryRequest::getType])) {
                $condition[] = [WechatTemplateEntity::getType, '=', $requestData[WechatTemplateQueryRequest::getType]];
            }

            $result = $this->wechatTemplateService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = WechatTemplateResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = WechatTemplateQueryResponse::from($result);
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

    #[OA\Post(path: '/wechatTemplate/store', summary: '新增微信消息模板接口', security: [['bearerAuth' => []]], tags: ['微信消息模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatTemplateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatTemplateResponse::class),
        ],
    ))]
    public function store(WechatTemplateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = WechatTemplateEntity::from($requestData);

            if ($this->wechatTemplateService->save($input->toEntity())) {
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

    #[OA\Get(path: '/wechatTemplate/show', summary: '获取微信消息模板详情接口', security: [['bearerAuth' => []]], tags: ['微信消息模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatTemplateResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $wechatTemplate = $this->wechatTemplateService->getOneById($id);
            if (empty($wechatTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = WechatTemplateResponse::from($wechatTemplate);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/wechatTemplate/update', summary: '更新微信消息模板接口', security: [['bearerAuth' => []]], tags: ['微信消息模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatTemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatTemplateResponse::class),
        ],
    ))]
    public function update(WechatTemplateUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $wechatTemplate = $this->wechatTemplateService->getOneById($id);
            if (empty($wechatTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = WechatTemplateEntity::from($requestData);

            $this->wechatTemplateService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/wechatTemplate/destroy', summary: '删除微信消息模板接口', security: [['bearerAuth' => []]], tags: ['微信消息模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WechatTemplateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: WechatTemplateDestroyResponse::class),
        ],
    ))]
    public function destroy(WechatTemplateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->wechatTemplateService->removeByIds($requestData['ids'])) {
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
