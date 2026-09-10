<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\AuditLogEntity;
use App\Services\AuditLogService;
use App\Api\Admin\Requests\AuditLog\AuditLogCreateRequest;
use App\Api\Admin\Requests\AuditLog\AuditLogDestroyRequest;
use App\Api\Admin\Requests\AuditLog\AuditLogQueryRequest;
use App\Api\Admin\Requests\AuditLog\AuditLogUpdateRequest;
use App\Api\Admin\Responses\AuditLog\AuditLogDestroyResponse;
use App\Api\Admin\Responses\AuditLog\AuditLogQueryResponse;
use App\Api\Admin\Responses\AuditLog\AuditLogResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AuditLogController extends BaseController
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    #[OA\Post(path: '/auditLog/search', summary: '查询审计日志列表接口', security: [['bearerAuth' => []]], tags: ['审计日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuditLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AuditLogQueryResponse::class),
        ],
    ))]
    public function search(AuditLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AuditLogQueryRequest::getCreatedAt])) {
                $condition[] = [AuditLogEntity::getCreatedAt, '=', $requestData[AuditLogQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[AuditLogQueryRequest::getTargetId])) {
                $condition[] = [AuditLogEntity::getTargetId, '=', $requestData[AuditLogQueryRequest::getTargetId]];
            }
            if (isset($requestData[AuditLogQueryRequest::getUserId])) {
                $condition[] = [AuditLogEntity::getUserId, '=', $requestData[AuditLogQueryRequest::getUserId]];
            }
            if (isset($requestData[AuditLogQueryRequest::getId])) {
                $condition[] = [AuditLogEntity::getId, '=', $requestData[AuditLogQueryRequest::getId]];
            }
            
            $result = $this->auditLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AuditLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AuditLogQueryResponse::from($result);
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

    #[OA\Post(path: '/auditLog/store', summary: '新增审计日志接口', security: [['bearerAuth' => []]], tags: ['审计日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuditLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AuditLogResponse::class),
        ],
    ))]
    public function store(AuditLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AuditLogEntity::from($requestData);

            if ($this->auditLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/auditLog/show', summary: '获取审计日志详情接口', security: [['bearerAuth' => []]], tags: ['审计日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AuditLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $auditLog = $this->auditLogService->getOneById($id);
            if (empty($auditLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AuditLogResponse::from($auditLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/auditLog/update', summary: '更新审计日志接口', security: [['bearerAuth' => []]], tags: ['审计日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuditLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AuditLogResponse::class),
        ],
    ))]
    public function update(AuditLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $auditLog = $this->auditLogService->getOneById($id);
            if (empty($auditLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AuditLogEntity::from($requestData);

            $this->auditLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/auditLog/destroy', summary: '删除审计日志接口', security: [['bearerAuth' => []]], tags: ['审计日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuditLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AuditLogDestroyResponse::class),
        ],
    ))]
    public function destroy(AuditLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->auditLogService->removeByIds($requestData['ids'])) {
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
