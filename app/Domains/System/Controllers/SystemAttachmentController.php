<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemAttachmentEntity;
use App\Domains\System\Requests\SystemAttachment\SystemAttachmentCreateRequest;
use App\Domains\System\Requests\SystemAttachment\SystemAttachmentDestroyRequest;
use App\Domains\System\Requests\SystemAttachment\SystemAttachmentQueryRequest;
use App\Domains\System\Requests\SystemAttachment\SystemAttachmentUpdateRequest;
use App\Domains\System\Responses\SystemAttachment\SystemAttachmentDestroyResponse;
use App\Domains\System\Responses\SystemAttachment\SystemAttachmentQueryResponse;
use App\Domains\System\Responses\SystemAttachment\SystemAttachmentResponse;
use App\Domains\System\Services\SystemAttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemAttachmentController extends BaseController
{
    public function __construct(
        private readonly SystemAttachmentService $systemAttachmentService,
    ) {}

    #[OA\Post(path: '/systemAttachment/search', summary: '查询附件列表接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAttachmentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAttachmentQueryResponse::class),
        ],
    ))]
    public function search(SystemAttachmentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemAttachmentQueryRequest::getModule])) {
                $condition[] = [SystemAttachmentEntity::getModule, '=', $requestData[SystemAttachmentQueryRequest::getModule]];
            }
            if (isset($requestData[SystemAttachmentQueryRequest::getId])) {
                $condition[] = [SystemAttachmentEntity::getId, '=', $requestData[SystemAttachmentQueryRequest::getId]];
            }

            $result = $this->systemAttachmentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemAttachmentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemAttachmentQueryResponse::from($result);
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

    #[OA\Post(path: '/systemAttachment/store', summary: '新增附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAttachmentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAttachmentResponse::class),
        ],
    ))]
    public function store(SystemAttachmentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemAttachmentEntity::from($requestData);

            if ($this->systemAttachmentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemAttachment/show', summary: '获取附件详情接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAttachmentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemAttachment = $this->systemAttachmentService->getOneById($id);
            if (empty($systemAttachment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemAttachmentResponse::from($systemAttachment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemAttachment/update', summary: '更新附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAttachmentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAttachmentResponse::class),
        ],
    ))]
    public function update(SystemAttachmentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemAttachment = $this->systemAttachmentService->getOneById($id);
            if (empty($systemAttachment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemAttachmentEntity::from($requestData);

            $this->systemAttachmentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemAttachment/destroy', summary: '删除附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAttachmentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAttachmentDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemAttachmentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemAttachmentService->removeByIds($requestData['ids'])) {
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
