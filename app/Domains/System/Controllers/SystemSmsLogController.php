<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemSmsLogEntity;
use App\Domains\System\Requests\SystemSmsLog\SystemSmsLogCreateRequest;
use App\Domains\System\Requests\SystemSmsLog\SystemSmsLogDestroyRequest;
use App\Domains\System\Requests\SystemSmsLog\SystemSmsLogQueryRequest;
use App\Domains\System\Requests\SystemSmsLog\SystemSmsLogUpdateRequest;
use App\Domains\System\Responses\SystemSmsLog\SystemSmsLogDestroyResponse;
use App\Domains\System\Responses\SystemSmsLog\SystemSmsLogQueryResponse;
use App\Domains\System\Responses\SystemSmsLog\SystemSmsLogResponse;
use App\Domains\System\Services\SystemSmsLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemSmsLogController extends BaseController
{
    public function __construct(
        private readonly SystemSmsLogService $systemSmsLogService,
    ) {}

    #[OA\Post(path: '/systemSmsLog/search', summary: '查询短信发送流水列表接口', security: [['bearerAuth' => []]], tags: ['短信发送流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSmsLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSmsLogQueryResponse::class),
        ],
    ))]
    public function search(SystemSmsLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemSmsLogQueryRequest::getPhone])) {
                $condition[] = [SystemSmsLogEntity::getPhone, '=', $requestData[SystemSmsLogQueryRequest::getPhone]];
            }
            if (isset($requestData[SystemSmsLogQueryRequest::getStatus])) {
                $condition[] = [SystemSmsLogEntity::getStatus, '=', $requestData[SystemSmsLogQueryRequest::getStatus]];
            }
            if (isset($requestData[SystemSmsLogQueryRequest::getCreateTime])) {
                $condition[] = [SystemSmsLogEntity::getCreateTime, '=', $requestData[SystemSmsLogQueryRequest::getCreateTime]];
            }
            if (isset($requestData[SystemSmsLogQueryRequest::getId])) {
                $condition[] = [SystemSmsLogEntity::getId, '=', $requestData[SystemSmsLogQueryRequest::getId]];
            }

            $result = $this->systemSmsLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemSmsLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemSmsLogQueryResponse::from($result);
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

    #[OA\Post(path: '/systemSmsLog/store', summary: '新增短信发送流水接口', security: [['bearerAuth' => []]], tags: ['短信发送流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSmsLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSmsLogResponse::class),
        ],
    ))]
    public function store(SystemSmsLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemSmsLogEntity::from($requestData);

            if ($this->systemSmsLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemSmsLog/show', summary: '获取短信发送流水详情接口', security: [['bearerAuth' => []]], tags: ['短信发送流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSmsLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemSmsLog = $this->systemSmsLogService->getOneById($id);
            if (empty($systemSmsLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemSmsLogResponse::from($systemSmsLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemSmsLog/update', summary: '更新短信发送流水接口', security: [['bearerAuth' => []]], tags: ['短信发送流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSmsLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSmsLogResponse::class),
        ],
    ))]
    public function update(SystemSmsLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemSmsLog = $this->systemSmsLogService->getOneById($id);
            if (empty($systemSmsLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemSmsLogEntity::from($requestData);

            $this->systemSmsLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemSmsLog/destroy', summary: '删除短信发送流水接口', security: [['bearerAuth' => []]], tags: ['短信发送流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSmsLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSmsLogDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemSmsLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemSmsLogService->removeByIds($requestData['ids'])) {
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
