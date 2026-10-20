<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemIpBlacklistEntity;
use App\Domains\System\Requests\SystemIpBlacklist\SystemIpBlacklistCreateRequest;
use App\Domains\System\Requests\SystemIpBlacklist\SystemIpBlacklistDestroyRequest;
use App\Domains\System\Requests\SystemIpBlacklist\SystemIpBlacklistQueryRequest;
use App\Domains\System\Requests\SystemIpBlacklist\SystemIpBlacklistUpdateRequest;
use App\Domains\System\Responses\SystemIpBlacklist\SystemIpBlacklistDestroyResponse;
use App\Domains\System\Responses\SystemIpBlacklist\SystemIpBlacklistQueryResponse;
use App\Domains\System\Responses\SystemIpBlacklist\SystemIpBlacklistResponse;
use App\Domains\System\Services\SystemIpBlacklistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemIpBlacklistController extends BaseController
{
    public function __construct(
        private readonly SystemIpBlacklistService $systemIpBlacklistService,
    ) {}

    #[OA\Post(path: '/systemIpBlacklist/search', summary: '查询IP黑名单列表接口', security: [['bearerAuth' => []]], tags: ['IP黑名单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemIpBlacklistQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemIpBlacklistQueryResponse::class),
        ],
    ))]
    public function search(SystemIpBlacklistQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemIpBlacklistQueryRequest::getId])) {
                $condition[] = [SystemIpBlacklistEntity::getId, '=', $requestData[SystemIpBlacklistQueryRequest::getId]];
            }
            if (isset($requestData[SystemIpBlacklistQueryRequest::getIp])) {
                $condition[] = [SystemIpBlacklistEntity::getIp, '=', $requestData[SystemIpBlacklistQueryRequest::getIp]];
            }

            $result = $this->systemIpBlacklistService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemIpBlacklistResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemIpBlacklistQueryResponse::from($result);
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
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemIpBlacklistCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemIpBlacklistResponse::class),
        ],
    ))]
    public function store(SystemIpBlacklistCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemIpBlacklistEntity::from($requestData);

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
            new OA\Property(property: 'data', ref: SystemIpBlacklistResponse::class),
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

            $response = SystemIpBlacklistResponse::from($systemIpBlacklist);

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
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemIpBlacklistUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemIpBlacklistResponse::class),
        ],
    ))]
    public function update(SystemIpBlacklistUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemIpBlacklist = $this->systemIpBlacklistService->getOneById($id);
            if (empty($systemIpBlacklist)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemIpBlacklistEntity::from($requestData);

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
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemIpBlacklistDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemIpBlacklistDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemIpBlacklistDestroyRequest $destroyRequest): JsonResponse
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
