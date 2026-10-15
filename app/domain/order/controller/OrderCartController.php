<?php

declare(strict_types=1);

namespace app\domain\order\controller;

use app\api\admin\controller\BaseController;
use app\domain\order\entity\OrderCartEntity;
use app\domain\order\request\orderCart\OrderCartCreateRequest;
use app\domain\order\request\orderCart\OrderCartQueryRequest;
use app\domain\order\request\orderCart\OrderCartUpdateRequest;
use app\domain\order\response\orderCart\OrderCartQueryResponse;
use app\domain\order\response\orderCart\OrderCartResponse;
use app\domain\order\service\OrderCartService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class OrderCartController extends BaseController
{
    #[OA\Post(path: '/orderCart/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderCartQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new OrderCartQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $orderCartService = new OrderCartService;
            $result = $orderCartService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderCartResponse;
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

    #[OA\Post(path: '/orderCart/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderCartCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderCartEntity = new OrderCartEntity;
            $orderCartEntity->loadData($request);

            $orderCartService = new OrderCartService;
            $insertId = $orderCartService->save($orderCartEntity->toEntity());
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

    #[OA\Get(path: '/orderCart/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderCartResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $orderCartService = new OrderCartService;
            $orderCart = $orderCartService->getOne($condition);

            if (empty($orderCart)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new OrderCartResponse;
            $response->loadData($orderCart);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/orderCart/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new OrderCartUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $orderCartService = new OrderCartService;
            $orderCart = $orderCartService->getById($request['id']);
            if (empty($orderCart)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $orderCartEntity = new OrderCartEntity;
            $orderCartEntity->loadData($request);

            $orderCartService->update($orderCartEntity->toEntity(), [
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

    #[OA\Delete(path: '/orderCart/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
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

            $orderCartService = new OrderCartService;
            if ($orderCartService->remove($condition)) {
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
