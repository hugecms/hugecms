<?php

declare(strict_types=1);

namespace app\domain\system\controller;

use app\api\admin\controller\BaseController;
use app\domain\system\entity\SystemSettingEntity;
use app\domain\system\request\systemSetting\SystemSettingCreateRequest;
use app\domain\system\request\systemSetting\SystemSettingQueryRequest;
use app\domain\system\request\systemSetting\SystemSettingUpdateRequest;
use app\domain\system\response\systemSetting\SystemSettingQueryResponse;
use app\domain\system\response\systemSetting\SystemSettingResponse;
use app\domain\system\service\SystemSettingService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SystemSettingController extends BaseController
{
    #[OA\Post(path: '/systemSetting/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['系统配置模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSettingQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemSettingQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SystemSettingQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $systemSettingService = new SystemSettingService;
            $result = $systemSettingService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SystemSettingResponse;
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

    #[OA\Post(path: '/systemSetting/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['系统配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSettingCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemSettingCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemSettingEntity = new SystemSettingEntity;
            $systemSettingEntity->loadData($request);

            $systemSettingService = new SystemSettingService;
            $insertId = $systemSettingService->save($systemSettingEntity->toEntity());
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

    #[OA\Get(path: '/systemSetting/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['系统配置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemSettingResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $systemSettingService = new SystemSettingService;
            $systemSetting = $systemSettingService->getOne($condition);

            if (empty($systemSetting)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SystemSettingResponse;
            $response->loadData($systemSetting);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/systemSetting/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['系统配置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemSettingUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemSettingUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemSettingService = new SystemSettingService;
            $systemSetting = $systemSettingService->getById($request['id']);
            if (empty($systemSetting)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $systemSettingEntity = new SystemSettingEntity;
            $systemSettingEntity->loadData($request);

            $systemSettingService->update($systemSettingEntity->toEntity(), [
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

    #[OA\Delete(path: '/systemSetting/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['系统配置模块'])]
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

            $systemSettingService = new SystemSettingService;
            if ($systemSettingService->remove($condition)) {
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
