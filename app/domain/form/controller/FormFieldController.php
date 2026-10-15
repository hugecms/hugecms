<?php

declare(strict_types=1);

namespace app\domain\form\controller;

use app\api\admin\controller\BaseController;
use app\domain\form\entity\FormFieldEntity;
use app\domain\form\request\formField\FormFieldCreateRequest;
use app\domain\form\request\formField\FormFieldQueryRequest;
use app\domain\form\request\formField\FormFieldUpdateRequest;
use app\domain\form\response\formField\FormFieldQueryResponse;
use app\domain\form\response\formField\FormFieldResponse;
use app\domain\form\service\FormFieldService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class FormFieldController extends BaseController
{
    #[OA\Post(path: '/formField/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormFieldQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new FormFieldQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $formFieldService = new FormFieldService;
            $result = $formFieldService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new FormFieldResponse;
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

    #[OA\Post(path: '/formField/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormFieldCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formFieldEntity = new FormFieldEntity;
            $formFieldEntity->loadData($request);

            $formFieldService = new FormFieldService;
            $insertId = $formFieldService->save($formFieldEntity->toEntity());
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

    #[OA\Get(path: '/formField/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormFieldResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $formFieldService = new FormFieldService;
            $formField = $formFieldService->getOne($condition);

            if (empty($formField)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new FormFieldResponse;
            $response->loadData($formField);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/formField/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormFieldUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormFieldUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formFieldService = new FormFieldService;
            $formField = $formFieldService->getById($request['id']);
            if (empty($formField)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $formFieldEntity = new FormFieldEntity;
            $formFieldEntity->loadData($request);

            $formFieldService->update($formFieldEntity->toEntity(), [
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

    #[OA\Delete(path: '/formField/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['表单字段模块'])]
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

            $formFieldService = new FormFieldService;
            if ($formFieldService->remove($condition)) {
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
