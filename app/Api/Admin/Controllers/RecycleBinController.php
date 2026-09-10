<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\RecycleBin\RecycleBinCreateRequest;
use App\Api\Admin\Requests\RecycleBin\RecycleBinDestroyRequest;
use App\Api\Admin\Requests\RecycleBin\RecycleBinQueryRequest;
use App\Api\Admin\Requests\RecycleBin\RecycleBinUpdateRequest;
use App\Api\Admin\Responses\RecycleBin\RecycleBinDestroyResponse;
use App\Api\Admin\Responses\RecycleBin\RecycleBinQueryResponse;
use App\Api\Admin\Responses\RecycleBin\RecycleBinResponse;
use App\Entities\RecycleBinEntity;
use App\Services\RecycleBinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class RecycleBinController extends BaseController
{
    public function __construct(
        private readonly RecycleBinService $recycleBinService,
    ) {}

    /**
     * 从回收站恢复（快照流程见 docs/development-conventions.md 第一节）。
     */
    #[OA\Post(path: '/recycleBin/restore', summary: '从回收站恢复接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(
        required: ['id'], properties: [new OA\Property(property: 'id', type: 'integer', example: 1)]))]
    public function restore(Request $request): JsonResponse
    {
        $id = \intval($request->input('id', '0'));

        try {
            $contentId = $this->recycleBinService->restore($id);

            return $this->success(['contentId' => $contentId]);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    /**
     * 彻底清除：物理删除目标及级联关联，并移除回收站记录。
     */
    #[OA\Post(path: '/recycleBin/purge', summary: '彻底清除接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(
        required: ['id'], properties: [new OA\Property(property: 'id', type: 'integer', example: 1)]))]
    public function purge(Request $request): JsonResponse
    {
        $id = \intval($request->input('id', '0'));

        try {
            $this->recycleBinService->purge($id);

            return $this->success();
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DELETE_ERROR);
        }
    }

    #[OA\Post(path: '/recycleBin/search', summary: '查询回收站列表接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RecycleBinQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RecycleBinQueryResponse::class),
        ],
    ))]
    public function search(RecycleBinQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[RecycleBinQueryRequest::getTargetType]) && $requestData[RecycleBinQueryRequest::getTargetType] !== '') {
                $condition[] = [RecycleBinEntity::getTargetType, '=', $requestData[RecycleBinQueryRequest::getTargetType]];
            }
            if (isset($requestData[RecycleBinQueryRequest::getId])) {
                $condition[] = [RecycleBinEntity::getId, '=', $requestData[RecycleBinQueryRequest::getId]];
            }
            if (isset($requestData[RecycleBinQueryRequest::getExpireAt])) {
                $condition[] = [RecycleBinEntity::getExpireAt, '=', $requestData[RecycleBinQueryRequest::getExpireAt]];
            }
            if (isset($requestData[RecycleBinQueryRequest::getTargetId])) {
                $condition[] = [RecycleBinEntity::getTargetId, '=', $requestData[RecycleBinQueryRequest::getTargetId]];
            }

            $result = $this->recycleBinService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = RecycleBinResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = RecycleBinQueryResponse::from($result);
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

    #[OA\Post(path: '/recycleBin/store', summary: '新增回收站接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RecycleBinCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RecycleBinResponse::class),
        ],
    ))]
    public function store(RecycleBinCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = RecycleBinEntity::from($requestData);

            if ($this->recycleBinService->save($input->toEntity())) {
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

    #[OA\Get(path: '/recycleBin/show', summary: '获取回收站详情接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RecycleBinResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $recycleBin = $this->recycleBinService->getOneById($id);
            if (empty($recycleBin)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = RecycleBinResponse::from($recycleBin);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/recycleBin/update', summary: '更新回收站接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RecycleBinUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RecycleBinResponse::class),
        ],
    ))]
    public function update(RecycleBinUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $recycleBin = $this->recycleBinService->getOneById($id);
            if (empty($recycleBin)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = RecycleBinEntity::from($requestData);

            $this->recycleBinService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/recycleBin/destroy', summary: '删除回收站接口', security: [['bearerAuth' => []]], tags: ['回收站模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RecycleBinDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RecycleBinDestroyResponse::class),
        ],
    ))]
    public function destroy(RecycleBinDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->recycleBinService->removeByIds($requestData['ids'])) {
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
