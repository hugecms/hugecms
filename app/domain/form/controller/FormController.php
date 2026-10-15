<?php

declare(strict_types=1);

namespace app\domain\form\controller;

use app\api\admin\controller\BaseController;
use app\domain\form\entity\FormEntity;
use app\domain\form\request\form\FormCreateRequest;
use app\domain\form\request\form\FormQueryRequest;
use app\domain\form\request\form\FormUpdateRequest;
use app\domain\form\response\form\FormQueryResponse;
use app\domain\form\response\form\FormResponse;
use app\domain\form\service\FormService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class FormController extends BaseController
{
    #[OA\Post(path: '/form/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new FormQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $formService = new FormService;
            $result = $formService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new FormResponse;
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

    #[OA\Post(path: '/form/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formEntity = new FormEntity;
            $formEntity->loadData($request);

            $formService = new FormService;
            $insertId = $formService->save($formEntity->toEntity());
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

    #[OA\Get(path: '/form/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $formService = new FormService;
            $form = $formService->getOne($condition);

            if (empty($form)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new FormResponse;
            $response->loadData($form);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/form/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formService = new FormService;
            $form = $formService->getById($request['id']);
            if (empty($form)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $formEntity = new FormEntity;
            $formEntity->loadData($request);

            $formService->update($formEntity->toEntity(), [
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

    #[OA\Delete(path: '/form/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['自定义表单模块'])]
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

            $formService = new FormService;
            if ($formService->remove($condition)) {
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
