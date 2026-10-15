<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderPaymentEntity;
use app\domain\order\service\OrderPaymentService;
use app\domain\order\request\orderPayment\OrderPaymentCreateRequest;
use app\domain\order\request\orderPayment\OrderPaymentQueryRequest;
use app\domain\order\request\orderPayment\OrderPaymentUpdateRequest;
use app\domain\order\response\orderPayment\OrderPaymentQueryResponse;
use app\domain\order\response\orderPayment\OrderPaymentResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderPaymentController extends BaseController
{
    #[OA\Post(path: '/orderPayment/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPaymentQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderPaymentQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderPaymentService = new OrderPaymentService;
            $result = $orderPaymentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderPaymentResponse;
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

    #[OA\Post(path: '/orderPayment/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderPaymentCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderPaymentEntity = new OrderPaymentEntity;
            $orderPaymentEntity->loadData($request);

            $orderPaymentService = new OrderPaymentService;
            $insertId = $orderPaymentService->save($orderPaymentEntity->toEntity());
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

    #[OA\Get(path: '/orderPayment/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPaymentResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderPaymentService = new OrderPaymentService;
            $orderPayment = $orderPaymentService->getOne($condition);

            if (empty($orderPayment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderPaymentResponse;
            $response->loadData($orderPayment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/orderPayment/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPaymentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderPaymentUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderPaymentService = new OrderPaymentService;
            $orderPayment = $orderPaymentService->getById($request['id']);
            if (empty($orderPayment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderPaymentEntity = new OrderPaymentEntity;
            $orderPaymentEntity->loadData($request);

            $orderPaymentService->update($orderPaymentEntity->toEntity(), [
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

    #[OA\Delete(path: '/orderPayment/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['支付单模块'])]
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

            $orderPaymentService = new OrderPaymentService;
            if ($orderPaymentService->remove($condition)) {
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
