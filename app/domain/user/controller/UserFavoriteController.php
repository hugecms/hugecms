<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserFavoriteEntity;
use app\domain\user\request\userFavorite\UserFavoriteCreateRequest;
use app\domain\user\request\userFavorite\UserFavoriteQueryRequest;
use app\domain\user\request\userFavorite\UserFavoriteUpdateRequest;
use app\domain\user\response\userFavorite\UserFavoriteQueryResponse;
use app\domain\user\response\userFavorite\UserFavoriteResponse;
use app\domain\user\service\UserFavoriteService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserFavoriteController extends BaseController
{
    #[OA\Post(path: '/userFavorite/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFavoriteQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserFavoriteQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userFavoriteService = new UserFavoriteService;
            $result = $userFavoriteService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserFavoriteResponse;
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

    #[OA\Post(path: '/userFavorite/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserFavoriteCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userFavoriteEntity = new UserFavoriteEntity;
            $userFavoriteEntity->loadData($request);

            $userFavoriteService = new UserFavoriteService;
            $insertId = $userFavoriteService->save($userFavoriteEntity->toEntity());
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

    #[OA\Get(path: '/userFavorite/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFavoriteResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userFavoriteService = new UserFavoriteService;
            $userFavorite = $userFavoriteService->getOne($condition);

            if (empty($userFavorite)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserFavoriteResponse;
            $response->loadData($userFavorite);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userFavorite/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFavoriteUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserFavoriteUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userFavoriteService = new UserFavoriteService;
            $userFavorite = $userFavoriteService->getById($request['id']);
            if (empty($userFavorite)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userFavoriteEntity = new UserFavoriteEntity;
            $userFavoriteEntity->loadData($request);

            $userFavoriteService->update($userFavoriteEntity->toEntity(), [
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

    #[OA\Delete(path: '/userFavorite/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
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

            $userFavoriteService = new UserFavoriteService;
            if ($userFavoriteService->remove($condition)) {
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
