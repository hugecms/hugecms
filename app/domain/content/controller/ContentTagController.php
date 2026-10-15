<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentTagEntity;
use app\domain\content\service\ContentTagService;
use app\domain\content\request\contentTag\ContentTagCreateRequest;
use app\domain\content\request\contentTag\ContentTagQueryRequest;
use app\domain\content\request\contentTag\ContentTagUpdateRequest;
use app\domain\content\response\contentTag\ContentTagQueryResponse;
use app\domain\content\response\contentTag\ContentTagResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentTagController extends BaseController
{
    #[OA\Post(path: '/contentTag/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentTagQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentTagQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentTagService = new ContentTagService;
            $result = $contentTagService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentTagResponse;
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

    #[OA\Post(path: '/contentTag/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentTagCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentTagEntity = new ContentTagEntity;
            $contentTagEntity->loadData($request);

            $contentTagService = new ContentTagService;
            $insertId = $contentTagService->save($contentTagEntity->toEntity());
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

    #[OA\Get(path: '/contentTag/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentTagResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentTagService = new ContentTagService;
            $contentTag = $contentTagService->getOne($condition);

            if (empty($contentTag)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentTagResponse;
            $response->loadData($contentTag);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentTag/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentTagUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentTagUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentTagService = new ContentTagService;
            $contentTag = $contentTagService->getById($request['id']);
            if (empty($contentTag)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentTagEntity = new ContentTagEntity;
            $contentTagEntity->loadData($request);

            $contentTagService->update($contentTagEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentTag/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['标签模块'])]
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

            $contentTagService = new ContentTagService;
            if ($contentTagService->remove($condition)) {
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
