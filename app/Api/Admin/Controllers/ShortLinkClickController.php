<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\ShortLinkClickEntity;
use App\Services\ShortLinkClickService;
use App\Api\Admin\Requests\ShortLinkClick\ShortLinkClickCreateRequest;
use App\Api\Admin\Requests\ShortLinkClick\ShortLinkClickDestroyRequest;
use App\Api\Admin\Requests\ShortLinkClick\ShortLinkClickQueryRequest;
use App\Api\Admin\Requests\ShortLinkClick\ShortLinkClickUpdateRequest;
use App\Api\Admin\Responses\ShortLinkClick\ShortLinkClickDestroyResponse;
use App\Api\Admin\Responses\ShortLinkClick\ShortLinkClickQueryResponse;
use App\Api\Admin\Responses\ShortLinkClick\ShortLinkClickResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShortLinkClickController extends BaseController
{
    public function __construct(
        private readonly ShortLinkClickService $shortLinkClickService,
    ) {}

    #[OA\Post(path: '/shortLinkClick/search', summary: '查询短链接点击明细列表接口', security: [['bearerAuth' => []]], tags: ['短链接点击明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkClickQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkClickQueryResponse::class),
        ],
    ))]
    public function search(ShortLinkClickQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShortLinkClickQueryRequest::getId])) {
                $condition[] = [ShortLinkClickEntity::getId, '=', $requestData[ShortLinkClickQueryRequest::getId]];
            }
            if (isset($requestData[ShortLinkClickQueryRequest::getCreatedAt])) {
                $condition[] = [ShortLinkClickEntity::getCreatedAt, '=', $requestData[ShortLinkClickQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[ShortLinkClickQueryRequest::getShortLinkId])) {
                $condition[] = [ShortLinkClickEntity::getShortLinkId, '=', $requestData[ShortLinkClickQueryRequest::getShortLinkId]];
            }
            
            $result = $this->shortLinkClickService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ShortLinkClickResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ShortLinkClickQueryResponse::from($result);
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

    #[OA\Post(path: '/shortLinkClick/store', summary: '新增短链接点击明细接口', security: [['bearerAuth' => []]], tags: ['短链接点击明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkClickCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkClickResponse::class),
        ],
    ))]
    public function store(ShortLinkClickCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ShortLinkClickEntity::from($requestData);

            if ($this->shortLinkClickService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shortLinkClick/show', summary: '获取短链接点击明细详情接口', security: [['bearerAuth' => []]], tags: ['短链接点击明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkClickResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $shortLinkClick = $this->shortLinkClickService->getOneById($id);
            if (empty($shortLinkClick)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ShortLinkClickResponse::from($shortLinkClick);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shortLinkClick/update', summary: '更新短链接点击明细接口', security: [['bearerAuth' => []]], tags: ['短链接点击明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkClickUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkClickResponse::class),
        ],
    ))]
    public function update(ShortLinkClickUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shortLinkClick = $this->shortLinkClickService->getOneById($id);
            if (empty($shortLinkClick)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ShortLinkClickEntity::from($requestData);

            $this->shortLinkClickService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/shortLinkClick/destroy', summary: '删除短链接点击明细接口', security: [['bearerAuth' => []]], tags: ['短链接点击明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkClickDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkClickDestroyResponse::class),
        ],
    ))]
    public function destroy(ShortLinkClickDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->shortLinkClickService->removeByIds($requestData['ids'])) {
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
