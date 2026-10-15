<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderShipmentEntity;
use app\domain\order\request\orderShipment\OrderShipmentCreateRequest;
use app\domain\order\request\orderShipment\OrderShipmentQueryRequest;
use app\domain\order\request\orderShipment\OrderShipmentUpdateRequest;
use app\domain\order\response\orderShipment\OrderShipmentQueryResponse;
use app\domain\order\response\orderShipment\OrderShipmentResponse;
use app\domain\order\service\OrderShipmentService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderShipmentController extends BaseController
{
    #[OA\Post(path: '/orderShipment/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['发货单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderShipmentQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderShipmentQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderShipmentService = new OrderShipmentService;
            $result = $orderShipmentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderShipmentResponse;
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

    #[OA\Post(path: '/orderShipment/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['发货单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderShipmentCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderShipmentEntity = new OrderShipmentEntity;
            $orderShipmentEntity->loadData($request);

            $orderShipmentService = new OrderShipmentService;
            $insertId = $orderShipmentService->save($orderShipmentEntity->toEntity());
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

    #[OA\Get(path: '/orderShipment/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['发货单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderShipmentResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderShipmentService = new OrderShipmentService;
            $orderShipment = $orderShipmentService->getOne($condition);

            if (empty($orderShipment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderShipmentResponse;
            $response->loadData($orderShipment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/orderShipment/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['发货单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderShipmentUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderShipmentService = new OrderShipmentService;
            $orderShipment = $orderShipmentService->getById($request['id']);
            if (empty($orderShipment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderShipmentEntity = new OrderShipmentEntity;
            $orderShipmentEntity->loadData($request);

            $orderShipmentService->update($orderShipmentEntity->toEntity(), [
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

    #[OA\Delete(path: '/orderShipment/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['发货单模块'])]
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

            $orderShipmentService = new OrderShipmentService;
            if ($orderShipmentService->remove($condition)) {
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
