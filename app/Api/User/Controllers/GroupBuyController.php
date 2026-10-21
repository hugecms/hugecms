<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Services\GroupBuyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GroupBuyController extends BaseController
{
    public function __construct(
        private readonly GroupBuyService $groupBuyService,
    ) {}

    /**
     * TODO: 用户认证落地后改为 $request->user()->id，当前以请求参数传入
     */
    private function resolveUserId(Request $request): int
    {
        return \intval($request->input('userId', '0'));
    }

    #[OA\Get(path: '/groupBuy/team', summary: '拼团详情', security: [['bearerAuth' => []]], tags: ['拼团'])]
    #[OA\Parameter(name: 'teamId', description: '团ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function team(Request $request): JsonResponse
    {
        try {
            $teamId = \intval($request->query('teamId', '0'));

            return $this->success($this->groupBuyService->teamDetail($teamId));
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/groupBuy/open', summary: '开团(下单后调用,orderNo 为订单号)', security: [['bearerAuth' => []]], tags: ['拼团'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function open(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $teamId = $this->groupBuyService->openTeam(
                $userId,
                \intval($request->input('activityId', '0')),
                \intval($request->input('skuId', '0')),
                (string) $request->input('orderNo', ''),
            );

            DB::commit();

            return $this->success(['teamId' => $teamId]);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Post(path: '/groupBuy/join', summary: '参团(下单后调用,orderNo 为订单号)', security: [['bearerAuth' => []]], tags: ['拼团'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function join(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $this->groupBuyService->joinTeam(
                $userId,
                \intval($request->input('teamId', '0')),
                (string) $request->input('orderNo', ''),
            );

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }
}
