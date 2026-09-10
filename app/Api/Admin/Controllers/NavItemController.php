<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\NavItemEntity;
use App\Services\NavItemService;
use App\Api\Admin\Requests\NavItem\NavItemCreateRequest;
use App\Api\Admin\Requests\NavItem\NavItemDestroyRequest;
use App\Api\Admin\Requests\NavItem\NavItemQueryRequest;
use App\Api\Admin\Requests\NavItem\NavItemUpdateRequest;
use App\Api\Admin\Responses\NavItem\NavItemDestroyResponse;
use App\Api\Admin\Responses\NavItem\NavItemQueryResponse;
use App\Api\Admin\Responses\NavItem\NavItemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class NavItemController extends BaseController
{
    public function __construct(
        private readonly NavItemService $navItemService,
    ) {}

    #[OA\Post(path: '/navItem/search', summary: '查询菜单项列表接口', security: [['bearerAuth' => []]], tags: ['菜单项模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavItemQueryResponse::class),
        ],
    ))]
    public function search(NavItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[NavItemQueryRequest::getParentId])) {
                $condition[] = [NavItemEntity::getParentId, '=', $requestData[NavItemQueryRequest::getParentId]];
            }
            if (isset($requestData[NavItemQueryRequest::getId])) {
                $condition[] = [NavItemEntity::getId, '=', $requestData[NavItemQueryRequest::getId]];
            }
            
            $result = $this->navItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = NavItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = NavItemQueryResponse::from($result);
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

    #[OA\Post(path: '/navItem/store', summary: '新增菜单项接口', security: [['bearerAuth' => []]], tags: ['菜单项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavItemResponse::class),
        ],
    ))]
    public function store(NavItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = NavItemEntity::from($requestData);

            if ($this->navItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/navItem/show', summary: '获取菜单项详情接口', security: [['bearerAuth' => []]], tags: ['菜单项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $navItem = $this->navItemService->getOneById($id);
            if (empty($navItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = NavItemResponse::from($navItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/navItem/update', summary: '更新菜单项接口', security: [['bearerAuth' => []]], tags: ['菜单项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavItemResponse::class),
        ],
    ))]
    public function update(NavItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $navItem = $this->navItemService->getOneById($id);
            if (empty($navItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = NavItemEntity::from($requestData);

            $this->navItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/navItem/destroy', summary: '删除菜单项接口', security: [['bearerAuth' => []]], tags: ['菜单项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavItemDestroyResponse::class),
        ],
    ))]
    public function destroy(NavItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->navItemService->removeByIds($requestData['ids'])) {
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
