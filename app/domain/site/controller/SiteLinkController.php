<?php

declare(strict_types=1);

namespace app\domain\site\controller;

use app\api\admin\controller\BaseController;
use app\domain\site\entity\SiteLinkEntity;
use app\domain\site\request\siteLink\SiteLinkCreateRequest;
use app\domain\site\request\siteLink\SiteLinkQueryRequest;
use app\domain\site\request\siteLink\SiteLinkUpdateRequest;
use app\domain\site\response\siteLink\SiteLinkQueryResponse;
use app\domain\site\response\siteLink\SiteLinkResponse;
use app\domain\site\service\SiteLinkService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SiteLinkController extends BaseController
{
    #[OA\Post(path: '/siteLink/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SiteLinkQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SiteLinkQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $siteLinkService = new SiteLinkService;
            $result = $siteLinkService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SiteLinkResponse;
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

    #[OA\Post(path: '/siteLink/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SiteLinkCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $siteLinkEntity = new SiteLinkEntity;
            $siteLinkEntity->loadData($request);

            $siteLinkService = new SiteLinkService;
            $insertId = $siteLinkService->save($siteLinkEntity->toEntity());
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

    #[OA\Get(path: '/siteLink/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SiteLinkResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $siteLinkService = new SiteLinkService;
            $siteLink = $siteLinkService->getOne($condition);

            if (empty($siteLink)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SiteLinkResponse;
            $response->loadData($siteLink);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/siteLink/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteLinkUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SiteLinkUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $siteLinkService = new SiteLinkService;
            $siteLink = $siteLinkService->getById($request['id']);
            if (empty($siteLink)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $siteLinkEntity = new SiteLinkEntity;
            $siteLinkEntity->loadData($request);

            $siteLinkService->update($siteLinkEntity->toEntity(), [
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

    #[OA\Delete(path: '/siteLink/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
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

            $siteLinkService = new SiteLinkService;
            if ($siteLinkService->remove($condition)) {
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
