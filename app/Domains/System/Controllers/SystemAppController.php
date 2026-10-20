<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemAppEntity;
use App\Domains\System\Requests\SystemApp\SystemAppCreateRequest;
use App\Domains\System\Requests\SystemApp\SystemAppDestroyRequest;
use App\Domains\System\Requests\SystemApp\SystemAppQueryRequest;
use App\Domains\System\Requests\SystemApp\SystemAppUpdateRequest;
use App\Domains\System\Responses\SystemApp\SystemAppDestroyResponse;
use App\Domains\System\Responses\SystemApp\SystemAppQueryResponse;
use App\Domains\System\Responses\SystemApp\SystemAppResponse;
use App\Domains\System\Services\SystemAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemAppController extends BaseController
{
    public function __construct(
        private readonly SystemAppService $systemAppService,
    ) {}

    #[OA\Post(path: '/systemApp/search', summary: '查询应用注册列表接口', security: [['bearerAuth' => []]], tags: ['应用注册模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAppQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAppQueryResponse::class),
        ],
    ))]
    public function search(SystemAppQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemAppQueryRequest::getId])) {
                $condition[] = [SystemAppEntity::getId, '=', $requestData[SystemAppQueryRequest::getId]];
            }
            if (isset($requestData[SystemAppQueryRequest::getAppid])) {
                $condition[] = [SystemAppEntity::getAppid, '=', $requestData[SystemAppQueryRequest::getAppid]];
            }
            if (isset($requestData[SystemAppQueryRequest::getCode])) {
                $condition[] = [SystemAppEntity::getCode, '=', $requestData[SystemAppQueryRequest::getCode]];
            }

            $result = $this->systemAppService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemAppResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemAppQueryResponse::from($result);
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

    #[OA\Post(path: '/systemApp/store', summary: '新增应用注册接口', security: [['bearerAuth' => []]], tags: ['应用注册模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAppCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAppResponse::class),
        ],
    ))]
    public function store(SystemAppCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemAppEntity::from($requestData);

            if ($this->systemAppService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemApp/show', summary: '获取应用注册详情接口', security: [['bearerAuth' => []]], tags: ['应用注册模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAppResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemApp = $this->systemAppService->getOneById($id);
            if (empty($systemApp)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemAppResponse::from($systemApp);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemApp/update', summary: '更新应用注册接口', security: [['bearerAuth' => []]], tags: ['应用注册模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAppUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAppResponse::class),
        ],
    ))]
    public function update(SystemAppUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemApp = $this->systemAppService->getOneById($id);
            if (empty($systemApp)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemAppEntity::from($requestData);

            $this->systemAppService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemApp/destroy', summary: '删除应用注册接口', security: [['bearerAuth' => []]], tags: ['应用注册模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemAppDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemAppDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemAppDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemAppService->removeByIds($requestData['ids'])) {
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
