<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemDictEntity;
use App\Domains\System\Requests\SystemDict\SystemDictCreateRequest;
use App\Domains\System\Requests\SystemDict\SystemDictDestroyRequest;
use App\Domains\System\Requests\SystemDict\SystemDictQueryRequest;
use App\Domains\System\Requests\SystemDict\SystemDictUpdateRequest;
use App\Domains\System\Responses\SystemDict\SystemDictDestroyResponse;
use App\Domains\System\Responses\SystemDict\SystemDictQueryResponse;
use App\Domains\System\Responses\SystemDict\SystemDictResponse;
use App\Domains\System\Services\SystemDictService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemDictController extends BaseController
{
    public function __construct(
        private readonly SystemDictService $systemDictService,
    ) {}

    #[OA\Post(path: '/systemDict/search', summary: '查询字典类型列表接口', security: [['bearerAuth' => []]], tags: ['字典类型模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictQueryResponse::class),
        ],
    ))]
    public function search(SystemDictQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemDictQueryRequest::getId])) {
                $condition[] = [SystemDictEntity::getId, '=', $requestData[SystemDictQueryRequest::getId]];
            }
            if (isset($requestData[SystemDictQueryRequest::getType])) {
                $condition[] = [SystemDictEntity::getType, '=', $requestData[SystemDictQueryRequest::getType]];
            }

            $result = $this->systemDictService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemDictResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemDictQueryResponse::from($result);
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

    #[OA\Post(path: '/systemDict/store', summary: '新增字典类型接口', security: [['bearerAuth' => []]], tags: ['字典类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictResponse::class),
        ],
    ))]
    public function store(SystemDictCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemDictEntity::from($requestData);

            if ($this->systemDictService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemDict/show', summary: '获取字典类型详情接口', security: [['bearerAuth' => []]], tags: ['字典类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemDict = $this->systemDictService->getOneById($id);
            if (empty($systemDict)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemDictResponse::from($systemDict);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemDict/update', summary: '更新字典类型接口', security: [['bearerAuth' => []]], tags: ['字典类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictResponse::class),
        ],
    ))]
    public function update(SystemDictUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemDict = $this->systemDictService->getOneById($id);
            if (empty($systemDict)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemDictEntity::from($requestData);

            $this->systemDictService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemDict/destroy', summary: '删除字典类型接口', security: [['bearerAuth' => []]], tags: ['字典类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemDictDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemDictDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemDictService->removeByIds($requestData['ids'])) {
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
