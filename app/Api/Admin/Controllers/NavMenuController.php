<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\NavMenuEntity;
use App\Services\NavMenuService;
use App\Api\Admin\Requests\NavMenu\NavMenuCreateRequest;
use App\Api\Admin\Requests\NavMenu\NavMenuDestroyRequest;
use App\Api\Admin\Requests\NavMenu\NavMenuQueryRequest;
use App\Api\Admin\Requests\NavMenu\NavMenuUpdateRequest;
use App\Api\Admin\Responses\NavMenu\NavMenuDestroyResponse;
use App\Api\Admin\Responses\NavMenu\NavMenuQueryResponse;
use App\Api\Admin\Responses\NavMenu\NavMenuResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class NavMenuController extends BaseController
{
    public function __construct(
        private readonly NavMenuService $navMenuService,
    ) {}

    #[OA\Post(path: '/navMenu/search', summary: '查询菜单集列表接口', security: [['bearerAuth' => []]], tags: ['菜单集模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavMenuQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavMenuQueryResponse::class),
        ],
    ))]
    public function search(NavMenuQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[NavMenuQueryRequest::getAlias])) {
                $condition[] = [NavMenuEntity::getAlias, '=', $requestData[NavMenuQueryRequest::getAlias]];
            }
            if (isset($requestData[NavMenuQueryRequest::getId])) {
                $condition[] = [NavMenuEntity::getId, '=', $requestData[NavMenuQueryRequest::getId]];
            }
            
            $result = $this->navMenuService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = NavMenuResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = NavMenuQueryResponse::from($result);
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

    #[OA\Post(path: '/navMenu/store', summary: '新增菜单集接口', security: [['bearerAuth' => []]], tags: ['菜单集模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavMenuCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavMenuResponse::class),
        ],
    ))]
    public function store(NavMenuCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = NavMenuEntity::from($requestData);

            if ($this->navMenuService->save($input->toEntity())) {
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

    #[OA\Get(path: '/navMenu/show', summary: '获取菜单集详情接口', security: [['bearerAuth' => []]], tags: ['菜单集模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavMenuResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $navMenu = $this->navMenuService->getOneById($id);
            if (empty($navMenu)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = NavMenuResponse::from($navMenu);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/navMenu/update', summary: '更新菜单集接口', security: [['bearerAuth' => []]], tags: ['菜单集模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavMenuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavMenuResponse::class),
        ],
    ))]
    public function update(NavMenuUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $navMenu = $this->navMenuService->getOneById($id);
            if (empty($navMenu)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = NavMenuEntity::from($requestData);

            $this->navMenuService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/navMenu/destroy', summary: '删除菜单集接口', security: [['bearerAuth' => []]], tags: ['菜单集模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: NavMenuDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: NavMenuDestroyResponse::class),
        ],
    ))]
    public function destroy(NavMenuDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->navMenuService->removeByIds($requestData['ids'])) {
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
