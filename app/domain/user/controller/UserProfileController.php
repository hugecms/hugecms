<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserProfileEntity;
use app\domain\user\service\UserProfileService;
use app\domain\user\request\userProfile\UserProfileCreateRequest;
use app\domain\user\request\userProfile\UserProfileQueryRequest;
use app\domain\user\request\userProfile\UserProfileUpdateRequest;
use app\domain\user\response\userProfile\UserProfileQueryResponse;
use app\domain\user\response\userProfile\UserProfileResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserProfileController extends BaseController
{
    #[OA\Post(path: '/userProfile/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserProfileQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userProfileService = new UserProfileService;
            $result = $userProfileService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserProfileResponse;
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

    #[OA\Post(path: '/userProfile/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserProfileCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userProfileEntity = new UserProfileEntity;
            $userProfileEntity->loadData($request);

            $userProfileService = new UserProfileService;
            $insertId = $userProfileService->save($userProfileEntity->toEntity());
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

    #[OA\Get(path: '/userProfile/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userProfileService = new UserProfileService;
            $userProfile = $userProfileService->getOne($condition);

            if (empty($userProfile)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserProfileResponse;
            $response->loadData($userProfile);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userProfile/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserProfileUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserProfileUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userProfileService = new UserProfileService;
            $userProfile = $userProfileService->getById($request['id']);
            if (empty($userProfile)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userProfileEntity = new UserProfileEntity;
            $userProfileEntity->loadData($request);

            $userProfileService->update($userProfileEntity->toEntity(), [
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

    #[OA\Delete(path: '/userProfile/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户资料模块'])]
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

            $userProfileService = new UserProfileService;
            if ($userProfileService->remove($condition)) {
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
