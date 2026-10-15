<?php

declare(strict_types=1);

namespace app\domain\product\controller;

use app\api\admin\controller\BaseController;
use app\domain\product\entity\ProductEntity;
use app\domain\product\service\ProductService;
use app\domain\product\request\product\ProductCreateRequest;
use app\domain\product\request\product\ProductQueryRequest;
use app\domain\product\request\product\ProductUpdateRequest;
use app\domain\product\response\product\ProductQueryResponse;
use app\domain\product\response\product\ProductResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ProductController extends BaseController
{
    #[OA\Post(path: '/product/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ProductQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $productService = new ProductService;
            $result = $productService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductResponse;
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

    #[OA\Post(path: '/product/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productEntity = new ProductEntity;
            $productEntity->loadData($request);

            $productService = new ProductService;
            $insertId = $productService->save($productEntity->toEntity());
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

    #[OA\Get(path: '/product/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $productService = new ProductService;
            $product = $productService->getOne($condition);

            if (empty($product)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ProductResponse;
            $response->loadData($product);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/product/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productService = new ProductService;
            $product = $productService->getById($request['id']);
            if (empty($product)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $productEntity = new ProductEntity;
            $productEntity->loadData($request);

            $productService->update($productEntity->toEntity(), [
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

    #[OA\Delete(path: '/product/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
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

            $productService = new ProductService;
            if ($productService->remove($condition)) {
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
