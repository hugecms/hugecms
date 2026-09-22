<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\FormSubmission\FormSubmissionCreateRequest;
use App\Api\Admin\Requests\FormSubmission\FormSubmissionDestroyRequest;
use App\Api\Admin\Requests\FormSubmission\FormSubmissionQueryRequest;
use App\Api\Admin\Requests\FormSubmission\FormSubmissionUpdateRequest;
use App\Api\Admin\Responses\FormSubmission\FormSubmissionDestroyResponse;
use App\Api\Admin\Responses\FormSubmission\FormSubmissionQueryResponse;
use App\Api\Admin\Responses\FormSubmission\FormSubmissionResponse;
use App\Entities\FormSubmissionEntity;
use App\Services\FormSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FormSubmissionController extends BaseController
{
    public function __construct(
        private readonly FormSubmissionService $formSubmissionService,
    ) {}

    #[OA\Post(path: '/formSubmission/search', summary: '查询表单提交列表接口', security: [['bearerAuth' => []]], tags: ['表单提交模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormSubmissionQueryResponse::class),
        ],
    ))]
    public function search(FormSubmissionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[FormSubmissionQueryRequest::getCreatedAt])) {
                $condition[] = [FormSubmissionEntity::getCreatedAt, '=', $requestData[FormSubmissionQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[FormSubmissionQueryRequest::getFormId])) {
                $condition[] = [FormSubmissionEntity::getFormId, '=', $requestData[FormSubmissionQueryRequest::getFormId]];
            }
            if (isset($requestData[FormSubmissionQueryRequest::getId])) {
                $condition[] = [FormSubmissionEntity::getId, '=', $requestData[FormSubmissionQueryRequest::getId]];
            }

            $result = $this->formSubmissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = FormSubmissionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = FormSubmissionQueryResponse::from($result);
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

    #[OA\Post(path: '/formSubmission/store', summary: '新增表单提交接口', security: [['bearerAuth' => []]], tags: ['表单提交模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormSubmissionResponse::class),
        ],
    ))]
    public function store(FormSubmissionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = FormSubmissionEntity::from($requestData);

            if ($this->formSubmissionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/formSubmission/show', summary: '获取表单提交详情接口', security: [['bearerAuth' => []]], tags: ['表单提交模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormSubmissionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $formSubmission = $this->formSubmissionService->getOneById($id);
            if (empty($formSubmission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = FormSubmissionResponse::from($formSubmission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/formSubmission/update', summary: '更新表单提交接口', security: [['bearerAuth' => []]], tags: ['表单提交模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormSubmissionResponse::class),
        ],
    ))]
    public function update(FormSubmissionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $formSubmission = $this->formSubmissionService->getOneById($id);
            if (empty($formSubmission)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = FormSubmissionEntity::from($requestData);

            $this->formSubmissionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/formSubmission/destroy', summary: '删除表单提交接口', security: [['bearerAuth' => []]], tags: ['表单提交模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormSubmissionDestroyResponse::class),
        ],
    ))]
    public function destroy(FormSubmissionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->formSubmissionService->removeByIds($requestData['ids'])) {
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
