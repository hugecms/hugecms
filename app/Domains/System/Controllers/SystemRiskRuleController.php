<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemRiskRuleEntity;
use App\Domains\System\Requests\SystemRiskRule\SystemRiskRuleCreateRequest;
use App\Domains\System\Requests\SystemRiskRule\SystemRiskRuleDestroyRequest;
use App\Domains\System\Requests\SystemRiskRule\SystemRiskRuleQueryRequest;
use App\Domains\System\Requests\SystemRiskRule\SystemRiskRuleUpdateRequest;
use App\Domains\System\Responses\SystemRiskRule\SystemRiskRuleDestroyResponse;
use App\Domains\System\Responses\SystemRiskRule\SystemRiskRuleQueryResponse;
use App\Domains\System\Responses\SystemRiskRule\SystemRiskRuleResponse;
use App\Domains\System\Services\SystemRiskRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemRiskRuleController extends BaseController
{
    public function __construct(
        private readonly SystemRiskRuleService $systemIpBlacklistService,
    ) {}

    #[OA\Post(path: '/systemIpBlacklist/search', summary: '查询IP黑名单列表接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRuleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRuleQueryResponse::class),
        ],
    ))]
    public function search(SystemRiskRuleQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemRiskRuleQueryRequest::getId])) {
                $condition[] = [SystemRiskRuleEntity::getId, '=', $requestData[SystemRiskRuleQueryRequest::getId]];
            }
            if (isset($requestData[SystemRiskRuleQueryRequest::getIp])) {
                $condition[] = [SystemRiskRuleEntity::getIp, '=', $requestData[SystemRiskRuleQueryRequest::getIp]];
            }

            $result = $this->systemIpBlacklistService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemRiskRuleResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemRiskRuleQueryResponse::from($result);
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

    #[OA\Post(path: '/systemIpBlacklist/store', summary: '新增IP黑名单接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRuleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRuleResponse::class),
        ],
    ))]
    public function store(SystemRiskRuleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemRiskRuleEntity::from($requestData);

            if ($this->systemIpBlacklistService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemIpBlacklist/show', summary: '获取IP黑名单详情接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRuleResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemIpBlacklist = $this->systemIpBlacklistService->getOneById($id);
            if (empty($systemIpBlacklist)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemRiskRuleResponse::from($systemIpBlacklist);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemIpBlacklist/update', summary: '更新IP黑名单接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRuleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRuleResponse::class),
        ],
    ))]
    public function update(SystemRiskRuleUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemIpBlacklist = $this->systemIpBlacklistService->getOneById($id);
            if (empty($systemIpBlacklist)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemRiskRuleEntity::from($requestData);

            $this->systemIpBlacklistService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemIpBlacklist/destroy', summary: '删除IP黑名单接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRiskRuleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemRiskRuleDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemRiskRuleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemIpBlacklistService->removeByIds($requestData['ids'])) {
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
