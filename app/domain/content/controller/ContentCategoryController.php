<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentCategoryEntity;
use app\domain\content\request\contentCategory\ContentCategoryCreateRequest;
use app\domain\content\request\contentCategory\ContentCategoryQueryRequest;
use app\domain\content\request\contentCategory\ContentCategoryUpdateRequest;
use app\domain\content\response\contentCategory\ContentCategoryQueryResponse;
use app\domain\content\response\contentCategory\ContentCategoryResponse;
use app\domain\content\service\ContentCategoryService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentCategoryController extends BaseController
{
    #[OA\Post(path: '/contentCategory/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentCategoryQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentCategoryQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentCategoryService = new ContentCategoryService;
            $result = $contentCategoryService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentCategoryResponse;
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

    #[OA\Post(path: '/contentCategory/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentCategoryCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentCategoryEntity = new ContentCategoryEntity;
            $contentCategoryEntity->loadData($request);

            $contentCategoryService = new ContentCategoryService;
            $insertId = $contentCategoryService->save($contentCategoryEntity->toEntity());
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

    #[OA\Get(path: '/contentCategory/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentCategoryResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentCategoryService = new ContentCategoryService;
            $contentCategory = $contentCategoryService->getOne($condition);

            if (empty($contentCategory)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentCategoryResponse;
            $response->loadData($contentCategory);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentCategory/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentCategoryUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentCategoryService = new ContentCategoryService;
            $contentCategory = $contentCategoryService->getById($request['id']);
            if (empty($contentCategory)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentCategoryEntity = new ContentCategoryEntity;
            $contentCategoryEntity->loadData($request);

            $contentCategoryService->update($contentCategoryEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentCategory/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['栏目模块'])]
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

            $contentCategoryService = new ContentCategoryService;
            if ($contentCategoryService->remove($condition)) {
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
