<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\FriendLinkEntity;
use App\Services\FriendLinkService;
use App\Api\Admin\Requests\FriendLink\FriendLinkCreateRequest;
use App\Api\Admin\Requests\FriendLink\FriendLinkDestroyRequest;
use App\Api\Admin\Requests\FriendLink\FriendLinkQueryRequest;
use App\Api\Admin\Requests\FriendLink\FriendLinkUpdateRequest;
use App\Api\Admin\Responses\FriendLink\FriendLinkDestroyResponse;
use App\Api\Admin\Responses\FriendLink\FriendLinkQueryResponse;
use App\Api\Admin\Responses\FriendLink\FriendLinkResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FriendLinkController extends BaseController
{
    public function __construct(
        private readonly FriendLinkService $friendLinkService,
    ) {}

    #[OA\Post(path: '/friendLink/search', summary: '查询友情链接列表接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FriendLinkQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FriendLinkQueryResponse::class),
        ],
    ))]
    public function search(FriendLinkQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[FriendLinkQueryRequest::getCategory])) {
                $condition[] = [FriendLinkEntity::getCategory, '=', $requestData[FriendLinkQueryRequest::getCategory]];
            }
            if (isset($requestData[FriendLinkQueryRequest::getStatus])) {
                $condition[] = [FriendLinkEntity::getStatus, '=', $requestData[FriendLinkQueryRequest::getStatus]];
            }
            if (isset($requestData[FriendLinkQueryRequest::getId])) {
                $condition[] = [FriendLinkEntity::getId, '=', $requestData[FriendLinkQueryRequest::getId]];
            }
            
            $result = $this->friendLinkService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = FriendLinkResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = FriendLinkQueryResponse::from($result);
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

    #[OA\Post(path: '/friendLink/store', summary: '新增友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FriendLinkCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FriendLinkResponse::class),
        ],
    ))]
    public function store(FriendLinkCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = FriendLinkEntity::from($requestData);

            if ($this->friendLinkService->save($input->toEntity())) {
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

    #[OA\Get(path: '/friendLink/show', summary: '获取友情链接详情接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FriendLinkResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $friendLink = $this->friendLinkService->getOneById($id);
            if (empty($friendLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = FriendLinkResponse::from($friendLink);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/friendLink/update', summary: '更新友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FriendLinkUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FriendLinkResponse::class),
        ],
    ))]
    public function update(FriendLinkUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $friendLink = $this->friendLinkService->getOneById($id);
            if (empty($friendLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = FriendLinkEntity::from($requestData);

            $this->friendLinkService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/friendLink/destroy', summary: '删除友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FriendLinkDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: FriendLinkDestroyResponse::class),
        ],
    ))]
    public function destroy(FriendLinkDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->friendLinkService->removeByIds($requestData['ids'])) {
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
