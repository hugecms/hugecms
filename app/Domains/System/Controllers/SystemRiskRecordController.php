<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemRiskRecordEntity;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordCreateRequest;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordDestroyRequest;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordQueryRequest;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordUpdateRequest;
use App\Domains\System\Responses\SystemRiskRecord\SystemRiskRecordDestroyResponse;
use App\Domains\System\Responses\SystemRiskRecord\SystemRiskRecordQueryResponse;
use App\Domains\System\Responses\SystemRiskRecord\SystemRiskRecordResponse;
use App\Domains\System\Services\SystemRiskRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemRiskRecordController extends BaseController
{
    public function __construct(
        private readonly SystemRiskRecordService $systemRiskRecordService,
    ) {}

    #[OA\Post(path: '/systemRiskRecord/search', summary: '查询风控事件列表接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRecordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRecordQueryResponse::class),
        ],
    ))]
    public function search(SystemRiskRecordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemRiskRecordQueryRequest::getObjectType])) {
                $condition[] = [SystemRiskRecordEntity::getObjectType, '=', $requestData[SystemRiskRecordQueryRequest::getObjectType]];
            }
            if (isset($requestData[SystemRiskRecordQueryRequest::getObjectNo])) {
                $condition[] = [SystemRiskRecordEntity::getObjectNo, '=', $requestData[SystemRiskRecordQueryRequest::getObjectNo]];
            }
            if (isset($requestData[SystemRiskRecordQueryRequest::getUserId])) {
                $condition[] = [SystemRiskRecordEntity::getUserId, '=', $requestData[SystemRiskRecordQueryRequest::getUserId]];
            }
            if (isset($requestData[SystemRiskRecordQueryRequest::getRuleId])) {
                $condition[] = [SystemRiskRecordEntity::getRuleId, '=', $requestData[SystemRiskRecordQueryRequest::getRuleId]];
            }
            if (isset($requestData[SystemRiskRecordQueryRequest::getId])) {
                $condition[] = [SystemRiskRecordEntity::getId, '=', $requestData[SystemRiskRecordQueryRequest::getId]];
            }

            $result = $this->systemRiskRecordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemRiskRecordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemRiskRecordQueryResponse::from($result);
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

    #[OA\Post(path: '/systemRiskRecord/store', summary: '新增风控事件接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRecordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRecordResponse::class),
        ],
    ))]
    public function store(SystemRiskRecordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemRiskRecordEntity::from($requestData);

            if ($this->systemRiskRecordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemRiskRecord/show', summary: '获取风控事件详情接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRecordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemRiskRecord = $this->systemRiskRecordService->getOneById($id);
            if (empty($systemRiskRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemRiskRecordResponse::from($systemRiskRecord);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemRiskRecord/update', summary: '更新风控事件接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRecordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRecordResponse::class),
        ],
    ))]
    public function update(SystemRiskRecordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemRiskRecord = $this->systemRiskRecordService->getOneById($id);
            if (empty($systemRiskRecord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemRiskRecordEntity::from($requestData);

            $this->systemRiskRecordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemRiskRecord/destroy', summary: '删除风控事件接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRecordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRecordDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemRiskRecordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemRiskRecordService->removeByIds($requestData['ids'])) {
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
