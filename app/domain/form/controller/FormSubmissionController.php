<?php

declare(strict_types=1);

namespace app\domain\form\controller;

use app\api\admin\controller\BaseController;
use app\domain\form\entity\FormSubmissionEntity;
use app\domain\form\service\FormSubmissionService;
use app\domain\form\request\formSubmission\FormSubmissionCreateRequest;
use app\domain\form\request\formSubmission\FormSubmissionQueryRequest;
use app\domain\form\request\formSubmission\FormSubmissionUpdateRequest;
use app\domain\form\response\formSubmission\FormSubmissionQueryResponse;
use app\domain\form\response\formSubmission\FormSubmissionResponse;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class FormSubmissionController extends BaseController
{
    #[OA\Post(path: '/formSubmission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['表单提交数据模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormSubmissionQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new FormSubmissionQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $formSubmissionService = new FormSubmissionService;
            $result = $formSubmissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new FormSubmissionResponse;
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

    #[OA\Post(path: '/formSubmission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['表单提交数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormSubmissionCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formSubmissionEntity = new FormSubmissionEntity;
            $formSubmissionEntity->loadData($request);

            $formSubmissionService = new FormSubmissionService;
            $insertId = $formSubmissionService->save($formSubmissionEntity->toEntity());
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

    #[OA\Get(path: '/formSubmission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['表单提交数据模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FormSubmissionResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $formSubmissionService = new FormSubmissionService;
            $formSubmission = $formSubmissionService->getOne($condition);

            if (empty($formSubmission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new FormSubmissionResponse;
            $response->loadData($formSubmission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/formSubmission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['表单提交数据模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FormSubmissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new FormSubmissionUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $formSubmissionService = new FormSubmissionService;
            $formSubmission = $formSubmissionService->getById($request['id']);
            if (empty($formSubmission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $formSubmissionEntity = new FormSubmissionEntity;
            $formSubmissionEntity->loadData($request);

            $formSubmissionService->update($formSubmissionEntity->toEntity(), [
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

    #[OA\Delete(path: '/formSubmission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['表单提交数据模块'])]
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

            $formSubmissionService = new FormSubmissionService;
            if ($formSubmissionService->remove($condition)) {
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
