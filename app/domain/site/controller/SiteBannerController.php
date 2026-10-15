<?php

declare(strict_types=1);

namespace app\domain\site\controller;

use app\api\admin\controller\BaseController;
use app\domain\site\entity\SiteBannerEntity;
use app\domain\site\service\SiteBannerService;
use app\domain\site\request\siteBanner\SiteBannerCreateRequest;
use app\domain\site\request\siteBanner\SiteBannerQueryRequest;
use app\domain\site\request\siteBanner\SiteBannerUpdateRequest;
use app\domain\site\response\siteBanner\SiteBannerQueryResponse;
use app\domain\site\response\siteBanner\SiteBannerResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SiteBannerController extends BaseController
{
    #[OA\Post(path: '/siteBanner/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SiteBannerQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SiteBannerQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $siteBannerService = new SiteBannerService;
            $result = $siteBannerService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SiteBannerResponse;
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

    #[OA\Post(path: '/siteBanner/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SiteBannerCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $siteBannerEntity = new SiteBannerEntity;
            $siteBannerEntity->loadData($request);

            $siteBannerService = new SiteBannerService;
            $insertId = $siteBannerService->save($siteBannerEntity->toEntity());
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

    #[OA\Get(path: '/siteBanner/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SiteBannerResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $siteBannerService = new SiteBannerService;
            $siteBanner = $siteBannerService->getOne($condition);

            if (empty($siteBanner)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SiteBannerResponse;
            $response->loadData($siteBanner);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/siteBanner/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SiteBannerUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SiteBannerUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $siteBannerService = new SiteBannerService;
            $siteBanner = $siteBannerService->getById($request['id']);
            if (empty($siteBanner)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $siteBannerEntity = new SiteBannerEntity;
            $siteBannerEntity->loadData($request);

            $siteBannerService->update($siteBannerEntity->toEntity(), [
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

    #[OA\Delete(path: '/siteBanner/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['轮播图/广告模块'])]
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

            $siteBannerService = new SiteBannerService;
            if ($siteBannerService->remove($condition)) {
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
