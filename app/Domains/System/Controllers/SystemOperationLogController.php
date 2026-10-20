<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemOperationLogEntity;
use App\Domains\System\Requests\SystemOperationLog\SystemOperationLogCreateRequest;
use App\Domains\System\Requests\SystemOperationLog\SystemOperationLogDestroyRequest;
use App\Domains\System\Requests\SystemOperationLog\SystemOperationLogQueryRequest;
use App\Domains\System\Requests\SystemOperationLog\SystemOperationLogUpdateRequest;
use App\Domains\System\Responses\SystemOperationLog\SystemOperationLogDestroyResponse;
use App\Domains\System\Responses\SystemOperationLog\SystemOperationLogQueryResponse;
use App\Domains\System\Responses\SystemOperationLog\SystemOperationLogResponse;
use App\Domains\System\Services\SystemOperationLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemOperationLogController extends BaseController
{
    public function __construct(
        private readonly SystemOperationLogService $systemOperationLogService,
    ) {}

    #[OA\Post(path: '/systemOperationLog/search', summary: '查询操作日志列表接口', security: [['bearerAuth' => []]], tags: ['操作日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemOperationLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemOperationLogQueryResponse::class),
        ],
    ))]
    public function search(SystemOperationLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemOperationLogQueryRequest::getCreateTime])) {
                $condition[] = [SystemOperationLogEntity::getCreateTime, '=', $requestData[SystemOperationLogQueryRequest::getCreateTime]];
            }
            if (isset($requestData[SystemOperationLogQueryRequest::getUserType])) {
                $condition[] = [SystemOperationLogEntity::getUserType, '=', $requestData[SystemOperationLogQueryRequest::getUserType]];
            }
            if (isset($requestData[SystemOperationLogQueryRequest::getUserId])) {
                $condition[] = [SystemOperationLogEntity::getUserId, '=', $requestData[SystemOperationLogQueryRequest::getUserId]];
            }
            if (isset($requestData[SystemOperationLogQueryRequest::getId])) {
                $condition[] = [SystemOperationLogEntity::getId, '=', $requestData[SystemOperationLogQueryRequest::getId]];
            }

            $result = $this->systemOperationLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemOperationLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemOperationLogQueryResponse::from($result);
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

    #[OA\Post(path: '/systemOperationLog/store', summary: '新增操作日志接口', security: [['bearerAuth' => []]], tags: ['操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemOperationLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemOperationLogResponse::class),
        ],
    ))]
    public function store(SystemOperationLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemOperationLogEntity::from($requestData);

            if ($this->systemOperationLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemOperationLog/show', summary: '获取操作日志详情接口', security: [['bearerAuth' => []]], tags: ['操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemOperationLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemOperationLog = $this->systemOperationLogService->getOneById($id);
            if (empty($systemOperationLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemOperationLogResponse::from($systemOperationLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemOperationLog/update', summary: '更新操作日志接口', security: [['bearerAuth' => []]], tags: ['操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemOperationLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemOperationLogResponse::class),
        ],
    ))]
    public function update(SystemOperationLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemOperationLog = $this->systemOperationLogService->getOneById($id);
            if (empty($systemOperationLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemOperationLogEntity::from($requestData);

            $this->systemOperationLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemOperationLog/destroy', summary: '删除操作日志接口', security: [['bearerAuth' => []]], tags: ['操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemOperationLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemOperationLogDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemOperationLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemOperationLogService->removeByIds($requestData['ids'])) {
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
