<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\ShortLinkEntity;
use App\Services\ShortLinkService;
use App\Api\Admin\Requests\ShortLink\ShortLinkCreateRequest;
use App\Api\Admin\Requests\ShortLink\ShortLinkDestroyRequest;
use App\Api\Admin\Requests\ShortLink\ShortLinkQueryRequest;
use App\Api\Admin\Requests\ShortLink\ShortLinkUpdateRequest;
use App\Api\Admin\Responses\ShortLink\ShortLinkDestroyResponse;
use App\Api\Admin\Responses\ShortLink\ShortLinkQueryResponse;
use App\Api\Admin\Responses\ShortLink\ShortLinkResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShortLinkController extends BaseController
{
    public function __construct(
        private readonly ShortLinkService $shortLinkService,
    ) {}

    #[OA\Post(path: '/shortLink/search', summary: '查询短链接列表接口', security: [['bearerAuth' => []]], tags: ['短链接模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkQueryResponse::class),
        ],
    ))]
    public function search(ShortLinkQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShortLinkQueryRequest::getId])) {
                $condition[] = [ShortLinkEntity::getId, '=', $requestData[ShortLinkQueryRequest::getId]];
            }
            if (isset($requestData[ShortLinkQueryRequest::getExpireAt])) {
                $condition[] = [ShortLinkEntity::getExpireAt, '=', $requestData[ShortLinkQueryRequest::getExpireAt]];
            }
            if (isset($requestData[ShortLinkQueryRequest::getShortCode])) {
                $condition[] = [ShortLinkEntity::getShortCode, '=', $requestData[ShortLinkQueryRequest::getShortCode]];
            }
            
            $result = $this->shortLinkService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ShortLinkResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ShortLinkQueryResponse::from($result);
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

    #[OA\Post(path: '/shortLink/store', summary: '新增短链接接口', security: [['bearerAuth' => []]], tags: ['短链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkResponse::class),
        ],
    ))]
    public function store(ShortLinkCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ShortLinkEntity::from($requestData);

            if ($this->shortLinkService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shortLink/show', summary: '获取短链接详情接口', security: [['bearerAuth' => []]], tags: ['短链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $shortLink = $this->shortLinkService->getOneById($id);
            if (empty($shortLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ShortLinkResponse::from($shortLink);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shortLink/update', summary: '更新短链接接口', security: [['bearerAuth' => []]], tags: ['短链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkResponse::class),
        ],
    ))]
    public function update(ShortLinkUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shortLink = $this->shortLinkService->getOneById($id);
            if (empty($shortLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ShortLinkEntity::from($requestData);

            $this->shortLinkService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/shortLink/destroy', summary: '删除短链接接口', security: [['bearerAuth' => []]], tags: ['短链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShortLinkDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ShortLinkDestroyResponse::class),
        ],
    ))]
    public function destroy(ShortLinkDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->shortLinkService->removeByIds($requestData['ids'])) {
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
