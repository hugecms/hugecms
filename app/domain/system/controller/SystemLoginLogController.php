<?php

declare(strict_types=1);

namespace app\domain\system\controller;

use app\api\admin\controller\BaseController;
use app\domain\system\entity\SystemLoginLogEntity;
use app\domain\system\service\SystemLoginLogService;
use app\domain\system\request\systemLoginLog\SystemLoginLogCreateRequest;
use app\domain\system\request\systemLoginLog\SystemLoginLogQueryRequest;
use app\domain\system\request\systemLoginLog\SystemLoginLogUpdateRequest;
use app\domain\system\response\systemLoginLog\SystemLoginLogQueryResponse;
use app\domain\system\response\systemLoginLog\SystemLoginLogResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SystemLoginLogController extends BaseController
{
    #[OA\Post(path: '/systemLoginLog/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemLoginLogQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SystemLoginLogQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $systemLoginLogService = new SystemLoginLogService;
            $result = $systemLoginLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SystemLoginLogResponse;
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

    #[OA\Post(path: '/systemLoginLog/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemLoginLogCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemLoginLogEntity = new SystemLoginLogEntity;
            $systemLoginLogEntity->loadData($request);

            $systemLoginLogService = new SystemLoginLogService;
            $insertId = $systemLoginLogService->save($systemLoginLogEntity->toEntity());
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

    #[OA\Get(path: '/systemLoginLog/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemLoginLogResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $systemLoginLogService = new SystemLoginLogService;
            $systemLoginLog = $systemLoginLogService->getOne($condition);

            if (empty($systemLoginLog)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SystemLoginLogResponse;
            $response->loadData($systemLoginLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/systemLoginLog/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemLoginLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemLoginLogUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemLoginLogService = new SystemLoginLogService;
            $systemLoginLog = $systemLoginLogService->getById($request['id']);
            if (empty($systemLoginLog)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $systemLoginLogEntity = new SystemLoginLogEntity;
            $systemLoginLogEntity->loadData($request);

            $systemLoginLogService->update($systemLoginLogEntity->toEntity(), [
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

    #[OA\Delete(path: '/systemLoginLog/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['登录日志模块'])]
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

            $systemLoginLogService = new SystemLoginLogService;
            if ($systemLoginLogService->remove($condition)) {
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
