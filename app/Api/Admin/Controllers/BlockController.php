<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Block\BlockCreateRequest;
use App\Api\Admin\Requests\Block\BlockDestroyRequest;
use App\Api\Admin\Requests\Block\BlockQueryRequest;
use App\Api\Admin\Requests\Block\BlockUpdateRequest;
use App\Api\Admin\Responses\Block\BlockDestroyResponse;
use App\Api\Admin\Responses\Block\BlockQueryResponse;
use App\Api\Admin\Responses\Block\BlockResponse;
use App\Entities\BlockEntity;
use App\Services\BlockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class BlockController extends BaseController
{
    public function __construct(
        private readonly BlockService $blockService,
    ) {}

    #[OA\Post(path: '/block/search', summary: '查询区块列表接口', security: [['bearerAuth' => []]], tags: ['区块模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BlockQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: BlockQueryResponse::class),
        ],
    ))]
    public function search(BlockQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->input('page', '1'));
        $pageSize = \intval($queryRequest->input('pageSize', '10'));
        $requestData = $queryRequest->all();

        try {
            $condition = [];
            if (! empty($requestData[BlockQueryRequest::getKeyword])) {
                $condition[] = [BlockEntity::getBlockName, 'like', '%'.$requestData[BlockQueryRequest::getKeyword].'%'];
            }
            if (isset($requestData[BlockQueryRequest::getBlockType])) {
                $condition[] = [BlockEntity::getBlockType, '=', $requestData[BlockQueryRequest::getBlockType]];
            }
            if (isset($requestData[BlockQueryRequest::getIsGlobal])) {
                $condition[] = [BlockEntity::getIsGlobal, '=', $requestData[BlockQueryRequest::getIsGlobal]];
            }
            if (isset($requestData[BlockQueryRequest::getId])) {
                $condition[] = [BlockEntity::getId, '=', $requestData[BlockQueryRequest::getId]];
            }

            $result = $this->blockService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = BlockResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = BlockQueryResponse::from($result);
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

    #[OA\Post(path: '/block/store', summary: '新增区块接口', security: [['bearerAuth' => []]], tags: ['区块模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BlockCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: BlockResponse::class),
        ],
    ))]
    public function store(BlockCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->all();

        DB::beginTransaction();
        try {
            $input = BlockEntity::from($requestData);

            if ($this->blockService->save($input->toEntity())) {
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

    #[OA\Get(path: '/block/show', summary: '获取区块详情接口', security: [['bearerAuth' => []]], tags: ['区块模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: BlockResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $block = $this->blockService->getOneById($id);
            if (empty($block)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = BlockResponse::from($block);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/block/update', summary: '更新区块接口', security: [['bearerAuth' => []]], tags: ['区块模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BlockUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: BlockResponse::class),
        ],
    ))]
    public function update(BlockUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->all();

        DB::beginTransaction();
        try {
            $block = $this->blockService->getOneById($id);
            if (empty($block)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = BlockEntity::from($requestData);

            $this->blockService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/block/destroy', summary: '删除区块接口', security: [['bearerAuth' => []]], tags: ['区块模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BlockDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: BlockDestroyResponse::class),
        ],
    ))]
    public function destroy(BlockDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->all();

        DB::beginTransaction();
        try {
            if ($this->blockService->removeByIds($requestData['ids'])) {
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
