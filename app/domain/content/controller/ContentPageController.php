<?php

declare(strict_types=1);

namespace app\domain\content\controller;

use app\api\admin\controller\BaseController;
use app\domain\content\entity\ContentPageEntity;
use app\domain\content\service\ContentPageService;
use app\domain\content\request\contentPage\ContentPageCreateRequest;
use app\domain\content\request\contentPage\ContentPageQueryRequest;
use app\domain\content\request\contentPage\ContentPageUpdateRequest;
use app\domain\content\response\contentPage\ContentPageQueryResponse;
use app\domain\content\response\contentPage\ContentPageResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class ContentPageController extends BaseController
{
    #[OA\Post(path: '/contentPage/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentPageQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new ContentPageQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $contentPageService = new ContentPageService;
            $result = $contentPageService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ContentPageResponse;
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

    #[OA\Post(path: '/contentPage/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentPageCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentPageEntity = new ContentPageEntity;
            $contentPageEntity->loadData($request);

            $contentPageService = new ContentPageService;
            $insertId = $contentPageService->save($contentPageEntity->toEntity());
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

    #[OA\Get(path: '/contentPage/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContentPageResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $contentPageService = new ContentPageService;
            $contentPage = $contentPageService->getOne($condition);

            if (empty($contentPage)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new ContentPageResponse;
            $response->loadData($contentPage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/contentPage/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContentPageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new ContentPageUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $contentPageService = new ContentPageService;
            $contentPage = $contentPageService->getById($request['id']);
            if (empty($contentPage)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $contentPageEntity = new ContentPageEntity;
            $contentPageEntity->loadData($request);

            $contentPageService->update($contentPageEntity->toEntity(), [
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

    #[OA\Delete(path: '/contentPage/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['单页模块'])]
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

            $contentPageService = new ContentPageService;
            if ($contentPageService->remove($condition)) {
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
