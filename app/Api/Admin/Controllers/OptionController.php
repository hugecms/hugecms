<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Option\OptionCreateRequest;
use App\Api\Admin\Requests\Option\OptionDestroyRequest;
use App\Api\Admin\Requests\Option\OptionQueryRequest;
use App\Api\Admin\Requests\Option\OptionUpdateRequest;
use App\Api\Admin\Responses\Option\OptionDestroyResponse;
use App\Api\Admin\Responses\Option\OptionQueryResponse;
use App\Api\Admin\Responses\Option\OptionResponse;
use App\Entities\OptionEntity;
use App\Services\OptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OptionController extends BaseController
{
    public function __construct(
        private readonly OptionService $optionService,
    ) {}

    #[OA\Post(path: '/option/search', summary: '查询全局配置列表接口', security: [['bearerAuth' => []]], tags: ['全局配置模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OptionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OptionQueryResponse::class),
        ],
    ))]
    public function search(OptionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (isset($requestData[OptionQueryRequest::getOptionKey])) {
                $condition[] = [OptionEntity::getOptionKey, '=', $requestData[OptionQueryRequest::getOptionKey]];
            }
            if (isset($requestData[OptionQueryRequest::getId])) {
                $condition[] = [OptionEntity::getId, '=', $requestData[OptionQueryRequest::getId]];
            }

            $result = $this->optionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OptionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OptionQueryResponse::from($result);
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

    #[OA\Post(path: '/option/store', summary: '新增全局配置接口', security: [['bearerAuth' => []]], tags: ['全局配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OptionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OptionResponse::class),
        ],
    ))]
    public function store(OptionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = OptionEntity::from($requestData);

            if ($this->optionService->save($input->toEntity())) {
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

    #[OA\Get(path: '/option/show', summary: '获取全局配置详情接口', security: [['bearerAuth' => []]], tags: ['全局配置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OptionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $option = $this->optionService->getOneById($id);
            if (empty($option)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OptionResponse::from($option);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/option/update', summary: '更新全局配置接口', security: [['bearerAuth' => []]], tags: ['全局配置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OptionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OptionResponse::class),
        ],
    ))]
    public function update(OptionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $option = $this->optionService->getOneById($id);
            if (empty($option)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OptionEntity::from($requestData);

            $this->optionService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/option/destroy', summary: '删除全局配置接口', security: [['bearerAuth' => []]], tags: ['全局配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OptionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OptionDestroyResponse::class),
        ],
    ))]
    public function destroy(OptionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->optionService->removeByIds($requestData['ids'])) {
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
