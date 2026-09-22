<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\AttachmentRelation\AttachmentRelationCreateRequest;
use App\Api\Admin\Requests\AttachmentRelation\AttachmentRelationDestroyRequest;
use App\Api\Admin\Requests\AttachmentRelation\AttachmentRelationQueryRequest;
use App\Api\Admin\Requests\AttachmentRelation\AttachmentRelationUpdateRequest;
use App\Api\Admin\Responses\AttachmentRelation\AttachmentRelationDestroyResponse;
use App\Api\Admin\Responses\AttachmentRelation\AttachmentRelationQueryResponse;
use App\Api\Admin\Responses\AttachmentRelation\AttachmentRelationResponse;
use App\Entities\AttachmentRelationEntity;
use App\Services\AttachmentRelationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AttachmentRelationController extends BaseController
{
    public function __construct(
        private readonly AttachmentRelationService $attachmentRelationService,
    ) {}

    #[OA\Post(path: '/attachmentRelation/search', summary: '查询内容附件关联列表接口', security: [['bearerAuth' => []]], tags: ['内容附件关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentRelationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentRelationQueryResponse::class),
        ],
    ))]
    public function search(AttachmentRelationQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[AttachmentRelationQueryRequest::getFieldKey])) {
                $condition[] = [AttachmentRelationEntity::getFieldKey, '=', $requestData[AttachmentRelationQueryRequest::getFieldKey]];
            }
            if (isset($requestData[AttachmentRelationQueryRequest::getContentId])) {
                $condition[] = [AttachmentRelationEntity::getContentId, '=', $requestData[AttachmentRelationQueryRequest::getContentId]];
            }
            if (isset($requestData[AttachmentRelationQueryRequest::getId])) {
                $condition[] = [AttachmentRelationEntity::getId, '=', $requestData[AttachmentRelationQueryRequest::getId]];
            }

            $result = $this->attachmentRelationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AttachmentRelationResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AttachmentRelationQueryResponse::from($result);
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

    #[OA\Post(path: '/attachmentRelation/store', summary: '新增内容附件关联接口', security: [['bearerAuth' => []]], tags: ['内容附件关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentRelationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentRelationResponse::class),
        ],
    ))]
    public function store(AttachmentRelationCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = AttachmentRelationEntity::from($requestData);

            if ($this->attachmentRelationService->save($input->toEntity())) {
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

    #[OA\Get(path: '/attachmentRelation/show', summary: '获取内容附件关联详情接口', security: [['bearerAuth' => []]], tags: ['内容附件关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentRelationResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $attachmentRelation = $this->attachmentRelationService->getOneById($id);
            if (empty($attachmentRelation)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AttachmentRelationResponse::from($attachmentRelation);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/attachmentRelation/update', summary: '更新内容附件关联接口', security: [['bearerAuth' => []]], tags: ['内容附件关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentRelationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentRelationResponse::class),
        ],
    ))]
    public function update(AttachmentRelationUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $attachmentRelation = $this->attachmentRelationService->getOneById($id);
            if (empty($attachmentRelation)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AttachmentRelationEntity::from($requestData);

            $this->attachmentRelationService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/attachmentRelation/destroy', summary: '删除内容附件关联接口', security: [['bearerAuth' => []]], tags: ['内容附件关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentRelationDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentRelationDestroyResponse::class),
        ],
    ))]
    public function destroy(AttachmentRelationDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->attachmentRelationService->removeByIds($requestData['ids'])) {
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
