<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\TermRelationshipEntity;
use App\Services\TermRelationshipService;
use App\Api\Admin\Requests\TermRelationship\TermRelationshipCreateRequest;
use App\Api\Admin\Requests\TermRelationship\TermRelationshipDestroyRequest;
use App\Api\Admin\Requests\TermRelationship\TermRelationshipQueryRequest;
use App\Api\Admin\Requests\TermRelationship\TermRelationshipUpdateRequest;
use App\Api\Admin\Responses\TermRelationship\TermRelationshipDestroyResponse;
use App\Api\Admin\Responses\TermRelationship\TermRelationshipQueryResponse;
use App\Api\Admin\Responses\TermRelationship\TermRelationshipResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class TermRelationshipController extends BaseController
{
    public function __construct(
        private readonly TermRelationshipService $termRelationshipService,
    ) {}

    #[OA\Post(path: '/termRelationship/search', summary: '查询内容分类关联列表接口', security: [['bearerAuth' => []]], tags: ['内容分类关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermRelationshipQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermRelationshipQueryResponse::class),
        ],
    ))]
    public function search(TermRelationshipQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[TermRelationshipQueryRequest::getId])) {
                $condition[] = [TermRelationshipEntity::getId, '=', $requestData[TermRelationshipQueryRequest::getId]];
            }
            if (isset($requestData[TermRelationshipQueryRequest::getTermId])) {
                $condition[] = [TermRelationshipEntity::getTermId, '=', $requestData[TermRelationshipQueryRequest::getTermId]];
            }
            if (isset($requestData[TermRelationshipQueryRequest::getTermId])) {
                $condition[] = [TermRelationshipEntity::getTermId, '=', $requestData[TermRelationshipQueryRequest::getTermId]];
            }
            
            $result = $this->termRelationshipService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = TermRelationshipResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = TermRelationshipQueryResponse::from($result);
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

    #[OA\Post(path: '/termRelationship/store', summary: '新增内容分类关联接口', security: [['bearerAuth' => []]], tags: ['内容分类关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermRelationshipCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermRelationshipResponse::class),
        ],
    ))]
    public function store(TermRelationshipCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = TermRelationshipEntity::from($requestData);

            if ($this->termRelationshipService->save($input->toEntity())) {
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

    #[OA\Get(path: '/termRelationship/show', summary: '获取内容分类关联详情接口', security: [['bearerAuth' => []]], tags: ['内容分类关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermRelationshipResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $termRelationship = $this->termRelationshipService->getOneById($id);
            if (empty($termRelationship)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = TermRelationshipResponse::from($termRelationship);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/termRelationship/update', summary: '更新内容分类关联接口', security: [['bearerAuth' => []]], tags: ['内容分类关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermRelationshipUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermRelationshipResponse::class),
        ],
    ))]
    public function update(TermRelationshipUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $termRelationship = $this->termRelationshipService->getOneById($id);
            if (empty($termRelationship)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = TermRelationshipEntity::from($requestData);

            $this->termRelationshipService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/termRelationship/destroy', summary: '删除内容分类关联接口', security: [['bearerAuth' => []]], tags: ['内容分类关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TermRelationshipDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: TermRelationshipDestroyResponse::class),
        ],
    ))]
    public function destroy(TermRelationshipDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->termRelationshipService->removeByIds($requestData['ids'])) {
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
