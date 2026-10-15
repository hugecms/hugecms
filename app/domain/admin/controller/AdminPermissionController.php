<?php

declare(strict_types=1);

namespace app\domain\admin\controller;

use app\api\admin\controller\BaseController;
use app\domain\admin\entity\AdminPermissionEntity;
use app\domain\admin\request\adminPermission\AdminPermissionCreateRequest;
use app\domain\admin\request\adminPermission\AdminPermissionQueryRequest;
use app\domain\admin\request\adminPermission\AdminPermissionUpdateRequest;
use app\domain\admin\response\adminPermission\AdminPermissionQueryResponse;
use app\domain\admin\response\adminPermission\AdminPermissionResponse;
use app\domain\admin\service\AdminPermissionService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class AdminPermissionController extends BaseController
{
    #[OA\Post(path: '/adminPermission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminPermissionQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new AdminPermissionQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $adminPermissionService = new AdminPermissionService;
            $result = $adminPermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdminPermissionResponse;
                $response->loadData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('查询列表错误');
        }
    }

    #[OA\Post(path: '/adminPermission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new AdminPermissionCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $adminPermissionEntity = new AdminPermissionEntity;
            $adminPermissionEntity->loadData($request);

            $adminPermissionService = new AdminPermissionService;
            $insertId = $adminPermissionService->save($adminPermissionEntity->toEntity());
            if ($insertId > 0) {
                DB::commit();

                return $this->success('新增数据成功');
            }

            throw new CustomException('新增数据失败');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('新增数据错误');
        }
    }

    #[OA\Get(path: '/adminPermission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminPermissionResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $adminPermissionService = new AdminPermissionService;
            $adminPermission = $adminPermissionService->getOne($condition);

            if (empty($adminPermission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new AdminPermissionResponse;
            $response->loadData($adminPermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/adminPermission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminPermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new AdminPermissionUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $adminPermissionService = new AdminPermissionService;
            $adminPermission = $adminPermissionService->getById($request['id']);
            if (empty($adminPermission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $adminPermissionEntity = new AdminPermissionEntity;
            $adminPermissionEntity->loadData($request);

            $adminPermissionService->update($adminPermissionEntity->toEntity(), [
                ['id', '=', $request['id']],
            ]);

            DB::commit();

            return $this->success('更新数据成功');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('更新数据错误');
        }
    }

    #[OA\Delete(path: '/adminPermission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['后台权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(): Response
    {
        DB::startTrans();
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $adminPermissionService = new AdminPermissionService;
            if ($adminPermissionService->remove($condition)) {
                DB::commit();

                return $this->success('删除数据成功');
            }

            throw new CustomException('删除数据失败');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('删除数据错误');
        }
    }
}
