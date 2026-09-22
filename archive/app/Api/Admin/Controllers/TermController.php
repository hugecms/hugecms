<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Term\TermCreateRequest;
use App\Api\Admin\Requests\Term\TermDestroyRequest;
use App\Api\Admin\Requests\Term\TermQueryRequest;
use App\Api\Admin\Requests\Term\TermUpdateRequest;
use App\Api\Admin\Responses\Term\TermDestroyResponse;
use App\Api\Admin\Responses\Term\TermQueryResponse;
use App\Api\Admin\Responses\Term\TermResponse;
use App\Entities\TermEntity;
use App\Services\TermService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class TermController extends BaseController
{
    public function __construct(
        private readonly TermService $termService,
    ) {}

    #[OA\Post(path: '/term/search', summary: '查询分类项列表接口', security: [['bearerAuth' => []]], tags: ['分类项模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermQueryResponse::class),
        ],
    ))]
    public function search(TermQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[TermQueryRequest::getKeyword])) {
                $condition[] = [TermEntity::getName, 'like', '%'.$requestData[TermQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[TermQueryRequest::getId])) {
                $condition[] = [TermEntity::getId, '=', $requestData[TermQueryRequest::getId]];
            }
            if (isset($requestData[TermQueryRequest::getParentId])) {
                $condition[] = [TermEntity::getParentId, '=', $requestData[TermQueryRequest::getParentId]];
            }
            if (isset($requestData[TermQueryRequest::getSlug])) {
                $condition[] = [TermEntity::getSlug, '=', $requestData[TermQueryRequest::getSlug]];
            }

            $result = $this->termService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = TermResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = TermQueryResponse::from($result);
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

    #[OA\Post(path: '/term/store', summary: '新增分类项接口', security: [['bearerAuth' => []]], tags: ['分类项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermResponse::class),
        ],
    ))]
    public function store(TermCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = TermEntity::from($requestData);

            if ($this->termService->save($input->toEntity())) {
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

    #[OA\Get(path: '/term/show', summary: '获取分类项详情接口', security: [['bearerAuth' => []]], tags: ['分类项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $term = $this->termService->getOneById($id);
            if (empty($term)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = TermResponse::from($term);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/term/update', summary: '更新分类项接口', security: [['bearerAuth' => []]], tags: ['分类项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermResponse::class),
        ],
    ))]
    public function update(TermUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $term = $this->termService->getOneById($id);
            if (empty($term)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = TermEntity::from($requestData);

            $this->termService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/term/destroy', summary: '删除分类项接口', security: [['bearerAuth' => []]], tags: ['分类项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermDestroyResponse::class),
        ],
    ))]
    public function destroy(TermDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->termService->removeByIds($requestData['ids'])) {
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
