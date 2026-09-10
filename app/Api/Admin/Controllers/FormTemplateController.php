<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\FormTemplateEntity;
use App\Services\FormTemplateService;
use App\Api\Admin\Requests\FormTemplate\FormTemplateCreateRequest;
use App\Api\Admin\Requests\FormTemplate\FormTemplateDestroyRequest;
use App\Api\Admin\Requests\FormTemplate\FormTemplateQueryRequest;
use App\Api\Admin\Requests\FormTemplate\FormTemplateUpdateRequest;
use App\Api\Admin\Responses\FormTemplate\FormTemplateDestroyResponse;
use App\Api\Admin\Responses\FormTemplate\FormTemplateQueryResponse;
use App\Api\Admin\Responses\FormTemplate\FormTemplateResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FormTemplateController extends BaseController
{
    public function __construct(
        private readonly FormTemplateService $formTemplateService,
    ) {}

    #[OA\Post(path: '/formTemplate/search', summary: '查询表单模板列表接口', security: [['bearerAuth' => []]], tags: ['表单模板模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormTemplateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormTemplateQueryResponse::class),
        ],
    ))]
    public function search(FormTemplateQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[FormTemplateQueryRequest::getAlias])) {
                $condition[] = [FormTemplateEntity::getAlias, '=', $requestData[FormTemplateQueryRequest::getAlias]];
            }
            if (isset($requestData[FormTemplateQueryRequest::getId])) {
                $condition[] = [FormTemplateEntity::getId, '=', $requestData[FormTemplateQueryRequest::getId]];
            }
            
            $result = $this->formTemplateService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = FormTemplateResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = FormTemplateQueryResponse::from($result);
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

    #[OA\Post(path: '/formTemplate/store', summary: '新增表单模板接口', security: [['bearerAuth' => []]], tags: ['表单模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormTemplateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormTemplateResponse::class),
        ],
    ))]
    public function store(FormTemplateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = FormTemplateEntity::from($requestData);

            if ($this->formTemplateService->save($input->toEntity())) {
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

    #[OA\Get(path: '/formTemplate/show', summary: '获取表单模板详情接口', security: [['bearerAuth' => []]], tags: ['表单模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormTemplateResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $formTemplate = $this->formTemplateService->getOneById($id);
            if (empty($formTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = FormTemplateResponse::from($formTemplate);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/formTemplate/update', summary: '更新表单模板接口', security: [['bearerAuth' => []]], tags: ['表单模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormTemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormTemplateResponse::class),
        ],
    ))]
    public function update(FormTemplateUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $formTemplate = $this->formTemplateService->getOneById($id);
            if (empty($formTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = FormTemplateEntity::from($requestData);

            $this->formTemplateService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/formTemplate/destroy', summary: '删除表单模板接口', security: [['bearerAuth' => []]], tags: ['表单模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormTemplateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormTemplateDestroyResponse::class),
        ],
    ))]
    public function destroy(FormTemplateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->formTemplateService->removeByIds($requestData['ids'])) {
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
