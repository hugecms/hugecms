<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemDictItemEntity;
use App\Domains\System\Services\SystemDictItemService;
use App\Domains\System\Requests\SystemDictItem\SystemDictItemCreateRequest;
use App\Domains\System\Requests\SystemDictItem\SystemDictItemDestroyRequest;
use App\Domains\System\Requests\SystemDictItem\SystemDictItemQueryRequest;
use App\Domains\System\Requests\SystemDictItem\SystemDictItemUpdateRequest;
use App\Domains\System\Responses\SystemDictItem\SystemDictItemDestroyResponse;
use App\Domains\System\Responses\SystemDictItem\SystemDictItemQueryResponse;
use App\Domains\System\Responses\SystemDictItem\SystemDictItemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemDictItemController extends BaseController
{
    public function __construct(
        private readonly SystemDictItemService $systemDictItemService,
    ) {}

    #[OA\Post(path: '/systemDictItem/search', summary: '查询字典项列表接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictItemQueryResponse::class),
        ],
    ))]
    public function search(SystemDictItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemDictItemQueryRequest::getDictId])) {
                $condition[] = [SystemDictItemEntity::getDictId, '=', $requestData[SystemDictItemQueryRequest::getDictId]];
            }
            if (isset($requestData[SystemDictItemQueryRequest::getId])) {
                $condition[] = [SystemDictItemEntity::getId, '=', $requestData[SystemDictItemQueryRequest::getId]];
            }
            
            $result = $this->systemDictItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemDictItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemDictItemQueryResponse::from($result);
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

    #[OA\Post(path: '/systemDictItem/store', summary: '新增字典项接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictItemResponse::class),
        ],
    ))]
    public function store(SystemDictItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemDictItemEntity::from($requestData);

            if ($this->systemDictItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemDictItem/show', summary: '获取字典项详情接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemDictItem = $this->systemDictItemService->getOneById($id);
            if (empty($systemDictItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemDictItemResponse::from($systemDictItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemDictItem/update', summary: '更新字典项接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictItemResponse::class),
        ],
    ))]
    public function update(SystemDictItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemDictItem = $this->systemDictItemService->getOneById($id);
            if (empty($systemDictItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemDictItemEntity::from($requestData);

            $this->systemDictItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemDictItem/destroy', summary: '删除字典项接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictItemDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemDictItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemDictItemService->removeByIds($requestData['ids'])) {
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
