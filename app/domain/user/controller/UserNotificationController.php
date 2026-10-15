<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserNotificationEntity;
use app\domain\user\request\userNotification\UserNotificationCreateRequest;
use app\domain\user\request\userNotification\UserNotificationQueryRequest;
use app\domain\user\request\userNotification\UserNotificationUpdateRequest;
use app\domain\user\response\userNotification\UserNotificationQueryResponse;
use app\domain\user\response\userNotification\UserNotificationResponse;
use app\domain\user\service\UserNotificationService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserNotificationController extends BaseController
{
    #[OA\Post(path: '/userNotification/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserNotificationQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserNotificationQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userNotificationService = new UserNotificationService;
            $result = $userNotificationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserNotificationResponse;
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

    #[OA\Post(path: '/userNotification/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserNotificationCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userNotificationEntity = new UserNotificationEntity;
            $userNotificationEntity->loadData($request);

            $userNotificationService = new UserNotificationService;
            $insertId = $userNotificationService->save($userNotificationEntity->toEntity());
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

    #[OA\Get(path: '/userNotification/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserNotificationResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userNotificationService = new UserNotificationService;
            $userNotification = $userNotificationService->getOne($condition);

            if (empty($userNotification)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserNotificationResponse;
            $response->loadData($userNotification);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userNotification/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserNotificationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserNotificationUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userNotificationService = new UserNotificationService;
            $userNotification = $userNotificationService->getById($request['id']);
            if (empty($userNotification)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userNotificationEntity = new UserNotificationEntity;
            $userNotificationEntity->loadData($request);

            $userNotificationService->update($userNotificationEntity->toEntity(), [
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

    #[OA\Delete(path: '/userNotification/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户通知模块'])]
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

            $userNotificationService = new UserNotificationService;
            if ($userNotificationService->remove($condition)) {
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
