<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemLoginLogEntity;
use App\Domains\System\Services\SystemLoginLogService;
use App\Domains\System\Requests\SystemLoginLog\SystemLoginLogCreateRequest;
use App\Domains\System\Requests\SystemLoginLog\SystemLoginLogDestroyRequest;
use App\Domains\System\Requests\SystemLoginLog\SystemLoginLogQueryRequest;
use App\Domains\System\Requests\SystemLoginLog\SystemLoginLogUpdateRequest;
use App\Domains\System\Responses\SystemLoginLog\SystemLoginLogDestroyResponse;
use App\Domains\System\Responses\SystemLoginLog\SystemLoginLogQueryResponse;
use App\Domains\System\Responses\SystemLoginLog\SystemLoginLogResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemLoginLogController extends BaseController
{
    public function __construct(
        private readonly SystemLoginLogService $systemLoginLogService,
    ) {}

    #[OA\Post(path: '/systemLoginLog/search', summary: '查询登录日志列表接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemLoginLogQueryResponse::class),
        ],
    ))]
    public function search(SystemLoginLogQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemLoginLogQueryRequest::getCreateTime])) {
                $condition[] = [SystemLoginLogEntity::getCreateTime, '=', $requestData[SystemLoginLogQueryRequest::getCreateTime]];
            }
            if (isset($requestData[SystemLoginLogQueryRequest::getIp])) {
                $condition[] = [SystemLoginLogEntity::getIp, '=', $requestData[SystemLoginLogQueryRequest::getIp]];
            }
            if (isset($requestData[SystemLoginLogQueryRequest::getUserId])) {
                $condition[] = [SystemLoginLogEntity::getUserId, '=', $requestData[SystemLoginLogQueryRequest::getUserId]];
            }
            if (isset($requestData[SystemLoginLogQueryRequest::getId])) {
                $condition[] = [SystemLoginLogEntity::getId, '=', $requestData[SystemLoginLogQueryRequest::getId]];
            }
            
            $result = $this->systemLoginLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemLoginLogResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemLoginLogQueryResponse::from($result);
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

    #[OA\Post(path: '/systemLoginLog/store', summary: '新增登录日志接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemLoginLogResponse::class),
        ],
    ))]
    public function store(SystemLoginLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemLoginLogEntity::from($requestData);

            if ($this->systemLoginLogService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemLoginLog/show', summary: '获取登录日志详情接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemLoginLogResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemLoginLog = $this->systemLoginLogService->getOneById($id);
            if (empty($systemLoginLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemLoginLogResponse::from($systemLoginLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemLoginLog/update', summary: '更新登录日志接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemLoginLogResponse::class),
        ],
    ))]
    public function update(SystemLoginLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemLoginLog = $this->systemLoginLogService->getOneById($id);
            if (empty($systemLoginLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemLoginLogEntity::from($requestData);

            $this->systemLoginLogService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemLoginLog/destroy', summary: '删除登录日志接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemLoginLogDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemLoginLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemLoginLogService->removeByIds($requestData['ids'])) {
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
