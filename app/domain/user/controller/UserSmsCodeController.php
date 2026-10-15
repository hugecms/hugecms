<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserSmsCodeEntity;
use app\domain\user\request\userSmsCode\UserSmsCodeCreateRequest;
use app\domain\user\request\userSmsCode\UserSmsCodeQueryRequest;
use app\domain\user\request\userSmsCode\UserSmsCodeUpdateRequest;
use app\domain\user\response\userSmsCode\UserSmsCodeQueryResponse;
use app\domain\user\response\userSmsCode\UserSmsCodeResponse;
use app\domain\user\service\UserSmsCodeService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserSmsCodeController extends BaseController
{
    #[OA\Post(path: '/userSmsCode/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserSmsCodeQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserSmsCodeQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userSmsCodeService = new UserSmsCodeService;
            $result = $userSmsCodeService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserSmsCodeResponse;
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

    #[OA\Post(path: '/userSmsCode/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserSmsCodeCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userSmsCodeEntity = new UserSmsCodeEntity;
            $userSmsCodeEntity->loadData($request);

            $userSmsCodeService = new UserSmsCodeService;
            $insertId = $userSmsCodeService->save($userSmsCodeEntity->toEntity());
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

    #[OA\Get(path: '/userSmsCode/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserSmsCodeResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userSmsCodeService = new UserSmsCodeService;
            $userSmsCode = $userSmsCodeService->getOne($condition);

            if (empty($userSmsCode)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserSmsCodeResponse;
            $response->loadData($userSmsCode);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userSmsCode/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserSmsCodeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserSmsCodeUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userSmsCodeService = new UserSmsCodeService;
            $userSmsCode = $userSmsCodeService->getById($request['id']);
            if (empty($userSmsCode)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userSmsCodeEntity = new UserSmsCodeEntity;
            $userSmsCodeEntity->loadData($request);

            $userSmsCodeService->update($userSmsCodeEntity->toEntity(), [
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

    #[OA\Delete(path: '/userSmsCode/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['短信验证码模块'])]
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

            $userSmsCodeService = new UserSmsCodeService;
            if ($userSmsCodeService->remove($condition)) {
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
