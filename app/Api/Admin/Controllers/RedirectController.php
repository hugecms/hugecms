<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Redirect\RedirectCreateRequest;
use App\Api\Admin\Requests\Redirect\RedirectDestroyRequest;
use App\Api\Admin\Requests\Redirect\RedirectQueryRequest;
use App\Api\Admin\Requests\Redirect\RedirectUpdateRequest;
use App\Api\Admin\Responses\Redirect\RedirectDestroyResponse;
use App\Api\Admin\Responses\Redirect\RedirectQueryResponse;
use App\Api\Admin\Responses\Redirect\RedirectResponse;
use App\Entities\RedirectEntity;
use App\Services\RedirectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class RedirectController extends BaseController
{
    public function __construct(
        private readonly RedirectService $redirectService,
    ) {}

    #[OA\Post(path: '/redirect/search', summary: '查询重定向列表接口', security: [['bearerAuth' => []]], tags: ['重定向模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RedirectQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RedirectQueryResponse::class),
        ],
    ))]
    public function search(RedirectQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[RedirectQueryRequest::getKeyword])) {
                $condition[] = [RedirectEntity::getSourcePath, 'like', '%'.$requestData[RedirectQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[RedirectQueryRequest::getId])) {
                $condition[] = [RedirectEntity::getId, '=', $requestData[RedirectQueryRequest::getId]];
            }
            if (isset($requestData[RedirectQueryRequest::getSourcePath])) {
                $condition[] = [RedirectEntity::getSourcePath, '=', $requestData[RedirectQueryRequest::getSourcePath]];
            }
            if (isset($requestData[RedirectQueryRequest::getStatus])) {
                $condition[] = [RedirectEntity::getStatus, '=', $requestData[RedirectQueryRequest::getStatus]];
            }

            $result = $this->redirectService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = RedirectResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = RedirectQueryResponse::from($result);
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

    #[OA\Post(path: '/redirect/store', summary: '新增重定向接口', security: [['bearerAuth' => []]], tags: ['重定向模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RedirectCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RedirectResponse::class),
        ],
    ))]
    public function store(RedirectCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = RedirectEntity::from($requestData);

            if ($this->redirectService->save($input->toEntity())) {
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

    #[OA\Get(path: '/redirect/show', summary: '获取重定向详情接口', security: [['bearerAuth' => []]], tags: ['重定向模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RedirectResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $redirect = $this->redirectService->getOneById($id);
            if (empty($redirect)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = RedirectResponse::from($redirect);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/redirect/update', summary: '更新重定向接口', security: [['bearerAuth' => []]], tags: ['重定向模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RedirectUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RedirectResponse::class),
        ],
    ))]
    public function update(RedirectUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $redirect = $this->redirectService->getOneById($id);
            if (empty($redirect)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = RedirectEntity::from($requestData);

            $this->redirectService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/redirect/destroy', summary: '删除重定向接口', security: [['bearerAuth' => []]], tags: ['重定向模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RedirectDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: RedirectDestroyResponse::class),
        ],
    ))]
    public function destroy(RedirectDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->redirectService->removeByIds($requestData['ids'])) {
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
