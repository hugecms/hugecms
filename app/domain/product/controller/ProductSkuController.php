<?php

declare(strict_types=1);

namespace app\domain\product\controller;

use app\api\admin\controller\BaseController;
use app\domain\product\entity\ProductSkuEntity;
use app\domain\product\service\ProductSkuService;
use app\domain\product\request\productSku\ProductSkuCreateRequest;
use app\domain\product\request\productSku\ProductSkuQueryRequest;
use app\domain\product\request\productSku\ProductSkuUpdateRequest;
use app\domain\product\response\productSku\ProductSkuQueryResponse;
use app\domain\product\response\productSku\ProductSkuResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ProductSkuController extends BaseController
{
    #[OA\Post(path: '/productSku/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品SKU模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ProductSkuQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $productSkuService = new ProductSkuService;
            $result = $productSkuService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductSkuResponse;
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

    #[OA\Post(path: '/productSku/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品SKU模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductSkuCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productSkuEntity = new ProductSkuEntity;
            $productSkuEntity->loadData($request);

            $productSkuService = new ProductSkuService;
            $insertId = $productSkuService->save($productSkuEntity->toEntity());
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

    #[OA\Get(path: '/productSku/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品SKU模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $productSkuService = new ProductSkuService;
            $productSku = $productSkuService->getOne($condition);

            if (empty($productSku)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ProductSkuResponse;
            $response->loadData($productSku);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/productSku/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品SKU模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductSkuUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productSkuService = new ProductSkuService;
            $productSku = $productSkuService->getById($request['id']);
            if (empty($productSku)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $productSkuEntity = new ProductSkuEntity;
            $productSkuEntity->loadData($request);

            $productSkuService->update($productSkuEntity->toEntity(), [
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

    #[OA\Delete(path: '/productSku/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品SKU模块'])]
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

            $productSkuService = new ProductSkuService;
            if ($productSkuService->remove($condition)) {
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
