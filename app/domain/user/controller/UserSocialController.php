<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserSocialEntity;
use app\domain\user\service\UserSocialService;
use app\domain\user\request\userSocial\UserSocialCreateRequest;
use app\domain\user\request\userSocial\UserSocialQueryRequest;
use app\domain\user\request\userSocial\UserSocialUpdateRequest;
use app\domain\user\response\userSocial\UserSocialQueryResponse;
use app\domain\user\response\userSocial\UserSocialResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserSocialController extends BaseController
{
    #[OA\Post(path: '/userSocial/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserSocialQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserSocialQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userSocialService = new UserSocialService;
            $result = $userSocialService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserSocialResponse;
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

    #[OA\Post(path: '/userSocial/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserSocialCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userSocialEntity = new UserSocialEntity;
            $userSocialEntity->loadData($request);

            $userSocialService = new UserSocialService;
            $insertId = $userSocialService->save($userSocialEntity->toEntity());
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

    #[OA\Get(path: '/userSocial/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserSocialResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userSocialService = new UserSocialService;
            $userSocial = $userSocialService->getOne($condition);

            if (empty($userSocial)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserSocialResponse;
            $response->loadData($userSocial);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userSocial/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSocialUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserSocialUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userSocialService = new UserSocialService;
            $userSocial = $userSocialService->getById($request['id']);
            if (empty($userSocial)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userSocialEntity = new UserSocialEntity;
            $userSocialEntity->loadData($request);

            $userSocialService->update($userSocialEntity->toEntity(), [
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

    #[OA\Delete(path: '/userSocial/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['社交登录绑定模块'])]
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

            $userSocialService = new UserSocialService;
            if ($userSocialService->remove($condition)) {
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
