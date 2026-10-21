<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Domains\User\Services\UserWithdrawOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class WithdrawOrderController extends BaseController
{
    public function __construct(
        private readonly UserWithdrawOrderService $userWithdrawOrderService,
    ) {}

    /**
     * TODO: 用户认证落地后改为 $request->user()->id，当前以请求参数传入
     */
    private function resolveUserId(Request $request): int
    {
        return \intval($request->input('userId', '0'));
    }

    #[OA\Post(path: '/withdrawOrder/apply', summary: '申请提现', security: [['bearerAuth' => []]], tags: ['提现'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function apply(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $id = $this->userWithdrawOrderService->apply($userId, [
                'amount' => $request->input('amount'),
                'accountType' => $request->input('accountType'),
                'accountName' => $request->input('accountName'),
                'accountNo' => $request->input('accountNo'),
                'userNote' => $request->input('userNote', ''),
            ]);

            DB::commit();

            return $this->success(['id' => $id]);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Post(path: '/withdrawOrder/search', summary: '我的提现单分页', security: [['bearerAuth' => []]], tags: ['提现'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function search(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $page = \intval($request->query('page', '1'));
            $pageSize = \intval($request->query('pageSize', '10'));

            $result = $this->userWithdrawOrderService->listMine($userId, $page, $pageSize);

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }
}
