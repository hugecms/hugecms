<?php

declare(strict_types=1);

namespace app\domain\system\controller;

use app\api\admin\controller\BaseController;
use app\domain\system\entity\SystemDictItemEntity;
use app\domain\system\request\systemDictItem\SystemDictItemCreateRequest;
use app\domain\system\request\systemDictItem\SystemDictItemQueryRequest;
use app\domain\system\request\systemDictItem\SystemDictItemUpdateRequest;
use app\domain\system\response\systemDictItem\SystemDictItemQueryResponse;
use app\domain\system\response\systemDictItem\SystemDictItemResponse;
use app\domain\system\service\SystemDictItemService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SystemDictItemController extends BaseController
{
    #[OA\Post(path: '/systemDictItem/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemDictItemQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SystemDictItemQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $systemDictItemService = new SystemDictItemService;
            $result = $systemDictItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SystemDictItemResponse;
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

    #[OA\Post(path: '/systemDictItem/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemDictItemCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemDictItemEntity = new SystemDictItemEntity;
            $systemDictItemEntity->loadData($request);

            $systemDictItemService = new SystemDictItemService;
            $insertId = $systemDictItemService->save($systemDictItemEntity->toEntity());
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

    #[OA\Get(path: '/systemDictItem/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemDictItemResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $systemDictItemService = new SystemDictItemService;
            $systemDictItem = $systemDictItemService->getOne($condition);

            if (empty($systemDictItem)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SystemDictItemResponse;
            $response->loadData($systemDictItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/systemDictItem/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemDictItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemDictItemUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemDictItemService = new SystemDictItemService;
            $systemDictItem = $systemDictItemService->getById($request['id']);
            if (empty($systemDictItem)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $systemDictItemEntity = new SystemDictItemEntity;
            $systemDictItemEntity->loadData($request);

            $systemDictItemService->update($systemDictItemEntity->toEntity(), [
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

    #[OA\Delete(path: '/systemDictItem/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['字典项模块'])]
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

            $systemDictItemService = new SystemDictItemService;
            if ($systemDictItemService->remove($condition)) {
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
