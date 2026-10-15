<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderEntity;
use app\domain\order\request\order\OrderCreateRequest;
use app\domain\order\request\order\OrderQueryRequest;
use app\domain\order\request\order\OrderUpdateRequest;
use app\domain\order\response\order\OrderQueryResponse;
use app\domain\order\response\order\OrderResponse;
use app\domain\order\service\OrderService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderController extends BaseController
{
    #[OA\Post(path: '/order/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['订单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderService = new OrderService;
            $result = $orderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderResponse;
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

    #[OA\Post(path: '/order/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderEntity = new OrderEntity;
            $orderEntity->loadData($request);

            $orderService = new OrderService;
            $insertId = $orderService->save($orderEntity->toEntity());
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

    #[OA\Get(path: '/order/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['订单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderService = new OrderService;
            $order = $orderService->getOne($condition);

            if (empty($order)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderResponse;
            $response->loadData($order);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/order/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderService = new OrderService;
            $order = $orderService->getById($request['id']);
            if (empty($order)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderEntity = new OrderEntity;
            $orderEntity->loadData($request);

            $orderService->update($orderEntity->toEntity(), [
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

    #[OA\Delete(path: '/order/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['订单模块'])]
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

            $orderService = new OrderService;
            if ($orderService->remove($condition)) {
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
