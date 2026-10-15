<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentCommentEntity;
use app\domain\content\service\ContentCommentService;
use app\domain\content\request\contentComment\ContentCommentCreateRequest;
use app\domain\content\request\contentComment\ContentCommentQueryRequest;
use app\domain\content\request\contentComment\ContentCommentUpdateRequest;
use app\domain\content\response\contentComment\ContentCommentQueryResponse;
use app\domain\content\response\contentComment\ContentCommentResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentCommentController extends BaseController
{
    #[OA\Post(path: '/contentComment/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentCommentQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentCommentQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentCommentService = new ContentCommentService;
            $result = $contentCommentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentCommentResponse;
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

    #[OA\Post(path: '/contentComment/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentCommentCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentCommentEntity = new ContentCommentEntity;
            $contentCommentEntity->loadData($request);

            $contentCommentService = new ContentCommentService;
            $insertId = $contentCommentService->save($contentCommentEntity->toEntity());
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

    #[OA\Get(path: '/contentComment/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentCommentResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentCommentService = new ContentCommentService;
            $contentComment = $contentCommentService->getOne($condition);

            if (empty($contentComment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentCommentResponse;
            $response->loadData($contentComment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentComment/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentCommentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentCommentUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentCommentService = new ContentCommentService;
            $contentComment = $contentCommentService->getById($request['id']);
            if (empty($contentComment)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentCommentEntity = new ContentCommentEntity;
            $contentCommentEntity->loadData($request);

            $contentCommentService->update($contentCommentEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentComment/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
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

            $contentCommentService = new ContentCommentService;
            if ($contentCommentService->remove($condition)) {
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
