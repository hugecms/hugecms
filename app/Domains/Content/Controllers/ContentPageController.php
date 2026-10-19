<?php

declare(strict_types=1);

namespace App\Domains\Content\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Content\Entities\ContentPageEntity;
use App\Domains\Content\Services\ContentPageService;
use App\Domains\Content\Requests\ContentPage\ContentPageCreateRequest;
use App\Domains\Content\Requests\ContentPage\ContentPageDestroyRequest;
use App\Domains\Content\Requests\ContentPage\ContentPageQueryRequest;
use App\Domains\Content\Requests\ContentPage\ContentPageUpdateRequest;
use App\Domains\Content\Responses\ContentPage\ContentPageDestroyResponse;
use App\Domains\Content\Responses\ContentPage\ContentPageQueryResponse;
use App\Domains\Content\Responses\ContentPage\ContentPageResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ContentPageController extends BaseController
{
    public function __construct(
        private readonly ContentPageService $contentPageService,
    ) {}

    #[OA\Post(path: '/contentPage/search', summary: '查询单页列表接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPageQueryResponse::class),
        ],
    ))]
    public function search(ContentPageQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ContentPageQueryRequest::getId])) {
                $condition[] = [ContentPageEntity::getId, '=', $requestData[ContentPageQueryRequest::getId]];
            }
            if (isset($requestData[ContentPageQueryRequest::getSlug])) {
                $condition[] = [ContentPageEntity::getSlug, '=', $requestData[ContentPageQueryRequest::getSlug]];
            }
            
            $result = $this->contentPageService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ContentPageResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ContentPageQueryResponse::from($result);
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

    #[OA\Post(path: '/contentPage/store', summary: '新增单页接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPageResponse::class),
        ],
    ))]
    public function store(ContentPageCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ContentPageEntity::from($requestData);

            if ($this->contentPageService->save($input->toEntity())) {
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

    #[OA\Get(path: '/contentPage/show', summary: '获取单页详情接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPageResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $contentPage = $this->contentPageService->getOneById($id);
            if (empty($contentPage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ContentPageResponse::from($contentPage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/contentPage/update', summary: '更新单页接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPageResponse::class),
        ],
    ))]
    public function update(ContentPageUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $contentPage = $this->contentPageService->getOneById($id);
            if (empty($contentPage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ContentPageEntity::from($requestData);

            $this->contentPageService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/contentPage/destroy', summary: '删除单页接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ContentPageDestroyResponse::class),
        ],
    ))]
    public function destroy(ContentPageDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->contentPageService->removeByIds($requestData['ids'])) {
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
