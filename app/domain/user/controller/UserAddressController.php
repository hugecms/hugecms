<?php

declare(strict_types=1);

namespace app\domain\user\controller;

use app\api\admin\controller\BaseController;
use app\domain\user\entity\UserAddressEntity;
use app\domain\user\request\userAddress\UserAddressCreateRequest;
use app\domain\user\request\userAddress\UserAddressQueryRequest;
use app\domain\user\request\userAddress\UserAddressUpdateRequest;
use app\domain\user\response\userAddress\UserAddressQueryResponse;
use app\domain\user\response\userAddress\UserAddressResponse;
use app\domain\user\service\UserAddressService;
use Juling\Foundation\Exception\CustomException;
use OpenApi\Attributes as OA;
use think\facade\Db as DB;
use think\facade\Log;
use think\Response;
use Throwable;

class UserAddressController extends BaseController
{
    #[OA\Post(path: '/userAddress/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->request->get();
            $page = intval($this->request->param('page', 1));
            $pageSize = intval($this->request->param('pageSize', 10));

            $v = new UserAddressQueryRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userAddressService = new UserAddressService;
            $result = $userAddressService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserAddressResponse;
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

    #[OA\Post(path: '/userAddress/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserAddressCreateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userAddressEntity = new UserAddressEntity;
            $userAddressEntity->loadData($request);

            $userAddressService = new UserAddressService;
            $insertId = $userAddressService->save($userAddressEntity->toEntity());
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

    #[OA\Get(path: '/userAddress/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->request->param('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userAddressService = new UserAddressService;
            $userAddress = $userAddressService->getOne($condition);

            if (empty($userAddress)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserAddressResponse;
            $response->loadData($userAddress);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e);

            return $this->error('获取详情错误');
        }
    }

    #[OA\Put(path: '/userAddress/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->request->get();

            $v = new UserAddressUpdateRequest;
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userAddressService = new UserAddressService;
            $userAddress = $userAddressService->getById($request['id']);
            if (empty($userAddress)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $userAddressEntity = new UserAddressEntity;
            $userAddressEntity->loadData($request);

            $userAddressService->update($userAddressEntity->toEntity(), [
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

    #[OA\Delete(path: '/userAddress/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户收货地址模块'])]
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

            $userAddressService = new UserAddressService;
            if ($userAddressService->remove($condition)) {
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
