<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\AdPosition\AdPositionCreateRequest;
use App\Api\Admin\Requests\AdPosition\AdPositionDestroyRequest;
use App\Api\Admin\Requests\AdPosition\AdPositionQueryRequest;
use App\Api\Admin\Requests\AdPosition\AdPositionUpdateRequest;
use App\Api\Admin\Responses\AdPosition\AdPositionDestroyResponse;
use App\Api\Admin\Responses\AdPosition\AdPositionQueryResponse;
use App\Api\Admin\Responses\AdPosition\AdPositionResponse;
use App\Entities\AdPositionEntity;
use App\Services\AdPositionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdPositionController extends BaseController
{
    public function __construct(
        private readonly AdPositionService $adPositionService,
    ) {}

    #[OA\Post(path: '/adPosition/search', summary: '查询广告位列表接口', security: [['bearerAuth' => []]], tags: ['广告位模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdPositionQueryResponse::class),
        ],
    ))]
    public function search(AdPositionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[AdPositionQueryRequest::getKeyword])) {
                $condition[] = [AdPositionEntity::getName, 'like', '%'.$requestData[AdPositionQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[AdPositionQueryRequest::getCode])) {
                $condition[] = [AdPositionEntity::getCode, '=', $requestData[AdPositionQueryRequest::getCode]];
            }
            if (isset($requestData[AdPositionQueryRequest::getId])) {
                $condition[] = [AdPositionEntity::getId, '=', $requestData[AdPositionQueryRequest::getId]];
            }

            $result = $this->adPositionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AdPositionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AdPositionQueryResponse::from($result);
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

    #[OA\Post(path: '/adPosition/store', summary: '新增广告位接口', security: [['bearerAuth' => []]], tags: ['广告位模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdPositionResponse::class),
        ],
    ))]
    public function store(AdPositionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = AdPositionEntity::from($requestData);

            if ($this->adPositionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adPosition/show', summary: '获取广告位详情接口', security: [['bearerAuth' => []]], tags: ['广告位模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdPositionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $adPosition = $this->adPositionService->getOneById($id);
            if (empty($adPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AdPositionResponse::from($adPosition);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adPosition/update', summary: '更新广告位接口', security: [['bearerAuth' => []]], tags: ['广告位模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdPositionResponse::class),
        ],
    ))]
    public function update(AdPositionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $adPosition = $this->adPositionService->getOneById($id);
            if (empty($adPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AdPositionEntity::from($requestData);

            $this->adPositionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/adPosition/destroy', summary: '删除广告位接口', security: [['bearerAuth' => []]], tags: ['广告位模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AdPositionDestroyResponse::class),
        ],
    ))]
    public function destroy(AdPositionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->adPositionService->removeByIds($requestData['ids'])) {
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
