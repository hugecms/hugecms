<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\PageTemplateEntity;
use App\Services\PageTemplateService;
use App\Api\Admin\Requests\PageTemplate\PageTemplateCreateRequest;
use App\Api\Admin\Requests\PageTemplate\PageTemplateDestroyRequest;
use App\Api\Admin\Requests\PageTemplate\PageTemplateQueryRequest;
use App\Api\Admin\Requests\PageTemplate\PageTemplateUpdateRequest;
use App\Api\Admin\Responses\PageTemplate\PageTemplateDestroyResponse;
use App\Api\Admin\Responses\PageTemplate\PageTemplateQueryResponse;
use App\Api\Admin\Responses\PageTemplate\PageTemplateResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PageTemplateController extends BaseController
{
    public function __construct(
        private readonly PageTemplateService $pageTemplateService,
    ) {}

    #[OA\Post(path: '/pageTemplate/search', summary: '查询页面模板列表接口', security: [['bearerAuth' => []]], tags: ['页面模板模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PageTemplateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PageTemplateQueryResponse::class),
        ],
    ))]
    public function search(PageTemplateQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PageTemplateQueryRequest::getCategory])) {
                $condition[] = [PageTemplateEntity::getCategory, '=', $requestData[PageTemplateQueryRequest::getCategory]];
            }
            if (isset($requestData[PageTemplateQueryRequest::getTemplateCode])) {
                $condition[] = [PageTemplateEntity::getTemplateCode, '=', $requestData[PageTemplateQueryRequest::getTemplateCode]];
            }
            if (isset($requestData[PageTemplateQueryRequest::getId])) {
                $condition[] = [PageTemplateEntity::getId, '=', $requestData[PageTemplateQueryRequest::getId]];
            }
            
            $result = $this->pageTemplateService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PageTemplateResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PageTemplateQueryResponse::from($result);
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

    #[OA\Post(path: '/pageTemplate/store', summary: '新增页面模板接口', security: [['bearerAuth' => []]], tags: ['页面模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PageTemplateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PageTemplateResponse::class),
        ],
    ))]
    public function store(PageTemplateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = PageTemplateEntity::from($requestData);

            if ($this->pageTemplateService->save($input->toEntity())) {
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

    #[OA\Get(path: '/pageTemplate/show', summary: '获取页面模板详情接口', security: [['bearerAuth' => []]], tags: ['页面模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PageTemplateResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $pageTemplate = $this->pageTemplateService->getOneById($id);
            if (empty($pageTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PageTemplateResponse::from($pageTemplate);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/pageTemplate/update', summary: '更新页面模板接口', security: [['bearerAuth' => []]], tags: ['页面模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PageTemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PageTemplateResponse::class),
        ],
    ))]
    public function update(PageTemplateUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $pageTemplate = $this->pageTemplateService->getOneById($id);
            if (empty($pageTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PageTemplateEntity::from($requestData);

            $this->pageTemplateService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/pageTemplate/destroy', summary: '删除页面模板接口', security: [['bearerAuth' => []]], tags: ['页面模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PageTemplateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PageTemplateDestroyResponse::class),
        ],
    ))]
    public function destroy(PageTemplateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->pageTemplateService->removeByIds($requestData['ids'])) {
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
