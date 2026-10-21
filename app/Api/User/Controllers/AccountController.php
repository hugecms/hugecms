<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Domains\User\Entities\UserAccountLogEntity;
use App\Domains\User\Entities\UserPointLogEntity;
use App\Domains\User\Services\UserAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AccountController extends BaseController
{
    public function __construct(
        private readonly UserAccountService $userAccountService,
    ) {}

    /**
     * TODO: 用户认证落地后改为 $request->user()->id，当前以请求参数传入
     */
    private function resolveUserId(Request $request): int
    {
        return \intval($request->input('userId', '0'));
    }

    #[OA\Get(path: '/account', summary: '我的账户概览', security: [['bearerAuth' => []]], tags: ['我的账户'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function account(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $account = $this->userAccountService->getAccount($userId);
            $points = (int) \DB::table('users')->where('id', $userId)->value('points');

            return $this->success(array_merge($account, ['points' => $points]));
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/accountLog/search', summary: '我的资金流水分页', security: [['bearerAuth' => []]], tags: ['我的账户'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function accountLog(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $page = \intval($request->query('page', '1'));
            $pageSize = \intval($request->query('pageSize', '10'));

            $condition = [[UserAccountLogEntity::getUserId, '=', $userId]];
            if ($request->filled('bizType')) {
                $condition[] = [UserAccountLogEntity::getBizType, '=', \intval($request->input('bizType'))];
            }

            $result = $this->userAccountService->pageLogs($condition, $page, $pageSize);

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/pointLog/search', summary: '我的积分流水分页', security: [['bearerAuth' => []]], tags: ['我的账户'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function pointLog(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $page = \intval($request->query('page', '1'));
            $pageSize = \intval($request->query('pageSize', '10'));

            $condition = [[UserPointLogEntity::getUserId, '=', $userId]];

            $result = \DB::table('user_point_logs')->where('user_id', $userId)->orderByDesc('id')
                ->forPage($page, $pageSize)->get()->toArray();

            return $this->success(['data' => $result]);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }
}
