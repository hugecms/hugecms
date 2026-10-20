<?php

declare(strict_types=1);

namespace App\Domains\Form\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Form\Entities\FormEntity;
use App\Domains\Form\Requests\Form\FormCreateRequest;
use App\Domains\Form\Requests\Form\FormDestroyRequest;
use App\Domains\Form\Requests\Form\FormQueryRequest;
use App\Domains\Form\Requests\Form\FormUpdateRequest;
use App\Domains\Form\Responses\Form\FormDestroyResponse;
use App\Domains\Form\Responses\Form\FormQueryResponse;
use App\Domains\Form\Responses\Form\FormResponse;
use App\Domains\Form\Services\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FormController extends BaseController
{
    public function __construct(
        private readonly FormService $formService,
    ) {}

    #[OA\Post(path: '/form/search', summary: '查询自定义表单列表接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormQueryResponse::class),
        ],
    ))]
    public function search(FormQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[FormQueryRequest::getId])) {
                $condition[] = [FormEntity::getId, '=', $requestData[FormQueryRequest::getId]];
            }

            $result = $this->formService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = FormResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = FormQueryResponse::from($result);
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

    #[OA\Post(path: '/form/store', summary: '新增自定义表单接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormResponse::class),
        ],
    ))]
    public function store(FormCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = FormEntity::from($requestData);

            if ($this->formService->save($input->toEntity())) {
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

    #[OA\Get(path: '/form/show', summary: '获取自定义表单详情接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $form = $this->formService->getOneById($id);
            if (empty($form)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = FormResponse::from($form);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/form/update', summary: '更新自定义表单接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormResponse::class),
        ],
    ))]
    public function update(FormUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $form = $this->formService->getOneById($id);
            if (empty($form)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = FormEntity::from($requestData);

            $this->formService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/form/destroy', summary: '删除自定义表单接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FormDestroyResponse::class),
        ],
    ))]
    public function destroy(FormDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->formService->removeByIds($requestData['ids'])) {
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
