<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderRefundEntity;
use app\domain\order\request\orderRefund\OrderRefundCreateRequest;
use app\domain\order\request\orderRefund\OrderRefundQueryRequest;
use app\domain\order\request\orderRefund\OrderRefundUpdateRequest;
use app\domain\order\response\orderRefund\OrderRefundQueryResponse;
use app\domain\order\response\orderRefund\OrderRefundResponse;
use app\domain\order\service\OrderRefundService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderRefundController extends BaseController
{
    #[OA\Post(path: '/orderRefund/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['退款单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderRefundQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderRefundQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderRefundService = new OrderRefundService;
            $result = $orderRefundService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderRefundResponse;
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

    #[OA\Post(path: '/orderRefund/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['退款单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderRefundCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderRefundEntity = new OrderRefundEntity;
            $orderRefundEntity->loadData($request);

            $orderRefundService = new OrderRefundService;
            $insertId = $orderRefundService->save($orderRefundEntity->toEntity());
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

    #[OA\Get(path: '/orderRefund/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['退款单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderRefundResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderRefundService = new OrderRefundService;
            $orderRefund = $orderRefundService->getOne($condition);

            if (empty($orderRefund)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderRefundResponse;
            $response->loadData($orderRefund);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/orderRefund/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['退款单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderRefundUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderRefundService = new OrderRefundService;
            $orderRefund = $orderRefundService->getById($request['id']);
            if (empty($orderRefund)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderRefundEntity = new OrderRefundEntity;
            $orderRefundEntity->loadData($request);

            $orderRefundService->update($orderRefundEntity->toEntity(), [
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

    #[OA\Delete(path: '/orderRefund/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['退款单模块'])]
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

            $orderRefundService = new OrderRefundService;
            if ($orderRefundService->remove($condition)) {
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
