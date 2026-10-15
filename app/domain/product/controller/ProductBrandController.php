<?php

declare(strict_types=1);

namespace app\domain\product\controller;

use app\api\admin\controller\BaseController;
use app\domain\product\entity\ProductBrandEntity;
use app\domain\product\service\ProductBrandService;
use app\domain\product\request\productBrand\ProductBrandCreateRequest;
use app\domain\product\request\productBrand\ProductBrandQueryRequest;
use app\domain\product\request\productBrand\ProductBrandUpdateRequest;
use app\domain\product\response\productBrand\ProductBrandQueryResponse;
use app\domain\product\response\productBrand\ProductBrandResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ProductBrandController extends BaseController
{
    #[OA\Post(path: '/productBrand/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductBrandQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ProductBrandQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $productBrandService = new ProductBrandService;
            $result = $productBrandService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductBrandResponse;
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

    #[OA\Post(path: '/productBrand/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductBrandCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productBrandEntity = new ProductBrandEntity;
            $productBrandEntity->loadData($request);

            $productBrandService = new ProductBrandService;
            $insertId = $productBrandService->save($productBrandEntity->toEntity());
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

    #[OA\Get(path: '/productBrand/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductBrandResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $productBrandService = new ProductBrandService;
            $productBrand = $productBrandService->getOne($condition);

            if (empty($productBrand)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ProductBrandResponse;
            $response->loadData($productBrand);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/productBrand/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBrandUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ProductBrandUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $productBrandService = new ProductBrandService;
            $productBrand = $productBrandService->getById($request['id']);
            if (empty($productBrand)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $productBrandEntity = new ProductBrandEntity;
            $productBrandEntity->loadData($request);

            $productBrandService->update($productBrandEntity->toEntity(), [
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

    #[OA\Delete(path: '/productBrand/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['品牌模块'])]
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

            $productBrandService = new ProductBrandService;
            if ($productBrandService->remove($condition)) {
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
