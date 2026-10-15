<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentArticleContentEntity;
use app\domain\content\service\ContentArticleContentService;
use app\domain\content\request\contentArticleContent\ContentArticleContentCreateRequest;
use app\domain\content\request\contentArticleContent\ContentArticleContentQueryRequest;
use app\domain\content\request\contentArticleContent\ContentArticleContentUpdateRequest;
use app\domain\content\response\contentArticleContent\ContentArticleContentQueryResponse;
use app\domain\content\response\contentArticleContent\ContentArticleContentResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentArticleContentController extends BaseController
{
    #[OA\Post(path: '/contentArticleContent/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentArticleContentQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentArticleContentQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentArticleContentService = new ContentArticleContentService;
            $result = $contentArticleContentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentArticleContentResponse;
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

    #[OA\Post(path: '/contentArticleContent/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentArticleContentCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentArticleContentEntity = new ContentArticleContentEntity;
            $contentArticleContentEntity->loadData($request);

            $contentArticleContentService = new ContentArticleContentService;
            $insertId = $contentArticleContentService->save($contentArticleContentEntity->toEntity());
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

    #[OA\Get(path: '/contentArticleContent/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentArticleContentResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentArticleContentService = new ContentArticleContentService;
            $contentArticleContent = $contentArticleContentService->getOne($condition);

            if (empty($contentArticleContent)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentArticleContentResponse;
            $response->loadData($contentArticleContent);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentArticleContent/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleContentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentArticleContentUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentArticleContentService = new ContentArticleContentService;
            $contentArticleContent = $contentArticleContentService->getById($request['id']);
            if (empty($contentArticleContent)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentArticleContentEntity = new ContentArticleContentEntity;
            $contentArticleContentEntity->loadData($request);

            $contentArticleContentService->update($contentArticleContentEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentArticleContent/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['文章正文模块'])]
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

            $contentArticleContentService = new ContentArticleContentService;
            if ($contentArticleContentService->remove($condition)) {
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
