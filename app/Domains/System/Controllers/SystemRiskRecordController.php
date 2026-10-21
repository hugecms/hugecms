<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemRiskRecordEntity;
use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordAppealRequest;
use App\Domains\System\Requests\SystemRiskRecord\SystemRiskRecordQueryRequest;
use App\Domains\System\Responses\SystemRiskRecord\SystemRiskRecordQueryResponse;
use App\Domains\System\Responses\SystemRiskRecord\SystemRiskRecordResponse;
use App\Services\RiskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemRiskRecordController extends BaseController
{
    public function __construct(
        private readonly RiskService $riskService,
        private readonly SystemRiskRecordRepository $recordRepository,
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
            if (isset($requestData[SystemRiskRecordQueryRequest::getUserId])) {
                $condition[] = [SystemRiskRecordEntity::getUserId, '=', $requestData[SystemRiskRecordQueryRequest::getUserId]];
            }
            if (isset($requestData[SystemRiskRecordQueryRequest::getRuleId])) {
                $condition[] = [SystemRiskRecordEntity::getRuleId, '=', $requestData[SystemRiskRecordQueryRequest::getRuleId]];
            }

            $result = $this->recordRepository->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $result['data'][$key] = SystemRiskRecordResponse::from($item)->toArray();
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
            $record = $this->recordRepository->findById($id);
            if (empty($record)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemRiskRecordResponse::from($record);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/systemRiskRecord/appeal', summary: '风控事件申诉处理接口', security: [['bearerAuth' => []]], tags: ['风控事件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRecordAppealRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function appeal(SystemRiskRecordAppealRequest $appealRequest): JsonResponse
    {
        $requestData = $appealRequest->post();

        try {
            $this->riskService->handleAppeal(
                \intval($requestData[SystemRiskRecordAppealRequest::getId]),
                \boolval($requestData[SystemRiskRecordAppealRequest::getPass]),
                (string) ($requestData[SystemRiskRecordAppealRequest::getHandleNote] ?? ''),
            );

            return $this->success();
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }
}
