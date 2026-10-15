<?php

declare(strict_types=1);

namespace app\domain\system\controller;

use app\api\admin\controller\BaseController;
use app\domain\system\entity\SystemRegionEntity;
use app\domain\system\request\systemRegion\SystemRegionCreateRequest;
use app\domain\system\request\systemRegion\SystemRegionQueryRequest;
use app\domain\system\request\systemRegion\SystemRegionUpdateRequest;
use app\domain\system\response\systemRegion\SystemRegionQueryResponse;
use app\domain\system\response\systemRegion\SystemRegionResponse;
use app\domain\system\service\SystemRegionService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class SystemRegionController extends BaseController
{
    #[OA\Post(path: '/systemRegion/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['行政区划模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRegionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemRegionQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new SystemRegionQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $systemRegionService = new SystemRegionService;
            $result = $systemRegionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SystemRegionResponse;
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

    #[OA\Post(path: '/systemRegion/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['行政区划模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRegionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemRegionCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemRegionEntity = new SystemRegionEntity;
            $systemRegionEntity->loadData($request);

            $systemRegionService = new SystemRegionService;
            $insertId = $systemRegionService->save($systemRegionEntity->toEntity());
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

    #[OA\Get(path: '/systemRegion/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['行政区划模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SystemRegionResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $systemRegionService = new SystemRegionService;
            $systemRegion = $systemRegionService->getOne($condition);

            if (empty($systemRegion)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new SystemRegionResponse;
            $response->loadData($systemRegion);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/systemRegion/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['行政区划模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SystemRegionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new SystemRegionUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $systemRegionService = new SystemRegionService;
            $systemRegion = $systemRegionService->getById($request['id']);
            if (empty($systemRegion)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $systemRegionEntity = new SystemRegionEntity;
            $systemRegionEntity->loadData($request);

            $systemRegionService->update($systemRegionEntity->toEntity(), [
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

    #[OA\Delete(path: '/systemRegion/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['行政区划模块'])]
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

            $systemRegionService = new SystemRegionService;
            if ($systemRegionService->remove($condition)) {
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
