<?php

declare(strict_types=1);

namespace App\Domains\System\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\System\Entities\SystemSensitiveWordEntity;
use App\Domains\System\Requests\SystemSensitiveWord\SystemSensitiveWordCreateRequest;
use App\Domains\System\Requests\SystemSensitiveWord\SystemSensitiveWordDestroyRequest;
use App\Domains\System\Requests\SystemSensitiveWord\SystemSensitiveWordQueryRequest;
use App\Domains\System\Requests\SystemSensitiveWord\SystemSensitiveWordUpdateRequest;
use App\Domains\System\Responses\SystemSensitiveWord\SystemSensitiveWordDestroyResponse;
use App\Domains\System\Responses\SystemSensitiveWord\SystemSensitiveWordQueryResponse;
use App\Domains\System\Responses\SystemSensitiveWord\SystemSensitiveWordResponse;
use App\Domains\System\Services\SystemSensitiveWordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SystemSensitiveWordController extends BaseController
{
    public function __construct(
        private readonly SystemSensitiveWordService $systemSensitiveWordService,
    ) {}

    #[OA\Post(path: '/systemSensitiveWord/search', summary: '查询敏感词列表接口', security: [['bearerAuth' => []]], tags: ['敏感词模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSensitiveWordQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSensitiveWordQueryResponse::class),
        ],
    ))]
    public function search(SystemSensitiveWordQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SystemSensitiveWordQueryRequest::getId])) {
                $condition[] = [SystemSensitiveWordEntity::getId, '=', $requestData[SystemSensitiveWordQueryRequest::getId]];
            }
            if (isset($requestData[SystemSensitiveWordQueryRequest::getWord])) {
                $condition[] = [SystemSensitiveWordEntity::getWord, '=', $requestData[SystemSensitiveWordQueryRequest::getWord]];
            }

            $result = $this->systemSensitiveWordService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = SystemSensitiveWordResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = SystemSensitiveWordQueryResponse::from($result);
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

    #[OA\Post(path: '/systemSensitiveWord/store', summary: '新增敏感词接口', security: [['bearerAuth' => []]], tags: ['敏感词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSensitiveWordCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSensitiveWordResponse::class),
        ],
    ))]
    public function store(SystemSensitiveWordCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = SystemSensitiveWordEntity::from($requestData);

            if ($this->systemSensitiveWordService->save($input->toEntity())) {
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

    #[OA\Get(path: '/systemSensitiveWord/show', summary: '获取敏感词详情接口', security: [['bearerAuth' => []]], tags: ['敏感词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSensitiveWordResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $systemSensitiveWord = $this->systemSensitiveWordService->getOneById($id);
            if (empty($systemSensitiveWord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = SystemSensitiveWordResponse::from($systemSensitiveWord);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/systemSensitiveWord/update', summary: '更新敏感词接口', security: [['bearerAuth' => []]], tags: ['敏感词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSensitiveWordUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSensitiveWordResponse::class),
        ],
    ))]
    public function update(SystemSensitiveWordUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $systemSensitiveWord = $this->systemSensitiveWordService->getOneById($id);
            if (empty($systemSensitiveWord)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = SystemSensitiveWordEntity::from($requestData);

            $this->systemSensitiveWordService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/systemSensitiveWord/destroy', summary: '删除敏感词接口', security: [['bearerAuth' => []]], tags: ['敏感词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSensitiveWordDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: SystemSensitiveWordDestroyResponse::class),
        ],
    ))]
    public function destroy(SystemSensitiveWordDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->systemSensitiveWordService->removeByIds($requestData['ids'])) {
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
