<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderItemEntity;
use app\domain\order\request\orderItem\OrderItemCreateRequest;
use app\domain\order\request\orderItem\OrderItemQueryRequest;
use app\domain\order\request\orderItem\OrderItemUpdateRequest;
use app\domain\order\response\orderItem\OrderItemQueryResponse;
use app\domain\order\response\orderItem\OrderItemResponse;
use app\domain\order\service\OrderItemService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderItemController extends BaseController
{
    #[OA\Post(path: '/orderItem/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['订单明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderItemQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderItemQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderItemService = new OrderItemService;
            $result = $orderItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderItemResponse;
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

    #[OA\Post(path: '/orderItem/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['订单明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderItemCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderItemEntity = new OrderItemEntity;
            $orderItemEntity->loadData($request);

            $orderItemService = new OrderItemService;
            $insertId = $orderItemService->save($orderItemEntity->toEntity());
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

    #[OA\Get(path: '/orderItem/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['订单明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderItemResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderItemService = new OrderItemService;
            $orderItem = $orderItemService->getOne($condition);

            if (empty($orderItem)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderItemResponse;
            $response->loadData($orderItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/orderItem/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['订单明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderItemUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderItemService = new OrderItemService;
            $orderItem = $orderItemService->getById($request['id']);
            if (empty($orderItem)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderItemEntity = new OrderItemEntity;
            $orderItemEntity->loadData($request);

            $orderItemService->update($orderItemEntity->toEntity(), [
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

    #[OA\Delete(path: '/orderItem/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['订单明细模块'])]
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

            $orderItemService = new OrderItemService;
            if ($orderItemService->remove($condition)) {
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
