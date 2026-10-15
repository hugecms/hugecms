<?php

declare(strict_types=1);

namespace app\domain\product\controller;

use app\api\admin\controller\BaseController;
use app\domain\product\entity\ProductStockLogEntity;
use app\domain\product\request\productStockLog\ProductStockLogCreateRequest;
use app\domain\product\request\productStockLog\ProductStockLogQueryRequest;
use app\domain\product\request\productStockLog\ProductStockLogUpdateRequest;
use app\domain\product\response\productStockLog\ProductStockLogQueryResponse;
use app\domain\product\response\productStockLog\ProductStockLogResponse;
use app\domain\product\service\ProductStockLogService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ProductStockLogController extends BaseController
{
    #[OA\Post(path: '/productStockLog/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductStockLogQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ProductStockLogQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $productStockLogService = new ProductStockLogService;
            $result = $productStockLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductStockLogResponse;
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

    #[OA\Post(path: '/productStockLog/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductStockLogCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productStockLogEntity = new ProductStockLogEntity;
            $productStockLogEntity->loadData($request);

            $productStockLogService = new ProductStockLogService;
            $insertId = $productStockLogService->save($productStockLogEntity->toEntity());
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

    #[OA\Get(path: '/productStockLog/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductStockLogResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $productStockLogService = new ProductStockLogService;
            $productStockLog = $productStockLogService->getOne($condition);

            if (empty($productStockLog)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ProductStockLogResponse;
            $response->loadData($productStockLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/productStockLog/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStockLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductStockLogUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productStockLogService = new ProductStockLogService;
            $productStockLog = $productStockLogService->getById($request['id']);
            if (empty($productStockLog)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $productStockLogEntity = new ProductStockLogEntity;
            $productStockLogEntity->loadData($request);

            $productStockLogService->update($productStockLogEntity->toEntity(), [
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

    #[OA\Delete(path: '/productStockLog/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['库存流水模块'])]
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

            $productStockLogService = new ProductStockLogService;
            if ($productStockLogService->remove($condition)) {
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
