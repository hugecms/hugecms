<?php

declare(strict_types=1);

namespace App\Domains\Form\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Form\Entities\FormFieldEntity;
use App\Domains\Form\Requests\FormField\FormFieldCreateRequest;
use App\Domains\Form\Requests\FormField\FormFieldDestroyRequest;
use App\Domains\Form\Requests\FormField\FormFieldQueryRequest;
use App\Domains\Form\Requests\FormField\FormFieldUpdateRequest;
use App\Domains\Form\Responses\FormField\FormFieldDestroyResponse;
use App\Domains\Form\Responses\FormField\FormFieldQueryResponse;
use App\Domains\Form\Responses\FormField\FormFieldResponse;
use App\Domains\Form\Services\FormFieldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FormFieldController extends BaseController
{
    public function __construct(
        private readonly FormFieldService $formFieldService,
    ) {}

    #[OA\Post(path: '/formField/search', summary: '查询表单字段列表接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormFieldQueryResponse::class),
        ],
    ))]
    public function search(FormFieldQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[FormFieldQueryRequest::getId])) {
                $condition[] = [FormFieldEntity::getId, '=', $requestData[FormFieldQueryRequest::getId]];
            }
            if (isset($requestData[FormFieldQueryRequest::getFormId])) {
                $condition[] = [FormFieldEntity::getFormId, '=', $requestData[FormFieldQueryRequest::getFormId]];
            }
            if (isset($requestData[FormFieldQueryRequest::getFieldName])) {
                $condition[] = [FormFieldEntity::getFieldName, '=', $requestData[FormFieldQueryRequest::getFieldName]];
            }

            $result = $this->formFieldService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = FormFieldResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = FormFieldQueryResponse::from($result);
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

    #[OA\Post(path: '/formField/store', summary: '新增表单字段接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormFieldResponse::class),
        ],
    ))]
    public function store(FormFieldCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = FormFieldEntity::from($requestData);

            if ($this->formFieldService->save($input->toEntity())) {
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

    #[OA\Get(path: '/formField/show', summary: '获取表单字段详情接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormFieldResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $formField = $this->formFieldService->getOneById($id);
            if (empty($formField)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = FormFieldResponse::from($formField);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/formField/update', summary: '更新表单字段接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormFieldResponse::class),
        ],
    ))]
    public function update(FormFieldUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $formField = $this->formFieldService->getOneById($id);
            if (empty($formField)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = FormFieldEntity::from($requestData);

            $this->formFieldService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/formField/destroy', summary: '删除表单字段接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormFieldDestroyResponse::class),
        ],
    ))]
    public function destroy(FormFieldDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->formFieldService->removeByIds($requestData['ids'])) {
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
