<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentArticleEntity;
use app\domain\content\service\ContentArticleService;
use app\domain\content\request\contentArticle\ContentArticleCreateRequest;
use app\domain\content\request\contentArticle\ContentArticleQueryRequest;
use app\domain\content\request\contentArticle\ContentArticleUpdateRequest;
use app\domain\content\response\contentArticle\ContentArticleQueryResponse;
use app\domain\content\response\contentArticle\ContentArticleResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentArticleController extends BaseController
{
    #[OA\Post(path: '/contentArticle/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentArticleQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentArticleQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentArticleService = new ContentArticleService;
            $result = $contentArticleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentArticleResponse;
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

    #[OA\Post(path: '/contentArticle/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentArticleCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentArticleEntity = new ContentArticleEntity;
            $contentArticleEntity->loadData($request);

            $contentArticleService = new ContentArticleService;
            $insertId = $contentArticleService->save($contentArticleEntity->toEntity());
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

    #[OA\Get(path: '/contentArticle/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentArticleResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentArticleService = new ContentArticleService;
            $contentArticle = $contentArticleService->getOne($condition);

            if (empty($contentArticle)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentArticleResponse;
            $response->loadData($contentArticle);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentArticle/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentArticleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentArticleUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentArticleService = new ContentArticleService;
            $contentArticle = $contentArticleService->getById($request['id']);
            if (empty($contentArticle)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentArticleEntity = new ContentArticleEntity;
            $contentArticleEntity->loadData($request);

            $contentArticleService->update($contentArticleEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentArticle/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
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

            $contentArticleService = new ContentArticleService;
            if ($contentArticleService->remove($condition)) {
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
