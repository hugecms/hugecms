<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Marketing\Repositories\MarketingAssistActivityRepository;
use App\Domains\Marketing\Repositories\MarketingAssistHelperRepository;
use App\Domains\Marketing\Repositories\MarketingAssistRecordRepository;
use App\Domains\User\Repositories\UserPointLogRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Throwable;

/**
 * 助力服务：发起/助力/达标发奖/过期
 * 防线：uk(activity,user) 一人一条参与记录、uk(record,helper) 一人一助力（只追加兜底刷单）；
 * helper_count 条件自增（where status=1），达标后条件更新 1→2 成功才发奖（发奖异常整事务回滚，幂等）；
 * 奖励两类：券写 marketing_user_coupons；积分走 users.points 自增 + user_point_logs（biz_type=8 活动奖励）
 */
class AssistService
{
    public function __construct(
        private readonly MarketingAssistActivityRepository $activityRepository,
        private readonly MarketingAssistRecordRepository $recordRepository,
        private readonly MarketingAssistHelperRepository $helperRepository,
        private readonly UserPointLogRepository $pointLogRepository,
        private readonly RiskService $riskService,
    ) {}

    /**
     * 发起助力：uk(activity,user) 兜底一人一条
     */
    public function initiate(int $userId, int $activityId): int
    {
        $this->getActiveActivity($activityId);

        try {
            return (int) $this->recordRepository->builder()->insertGetId([
                'activity_id' => $activityId,
                'user_id' => $userId,
                'helper_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException) {
            throw new BusinessException('您已发起过该活动', 40053);
        }
    }

    /**
     * 助力：helper 插入（uk 兜底一人一助力）→ 计数条件自增 → 达标条件更新发奖
     */
    public function assist(int $helperUserId, int $recordId): void
    {
        $record = $this->getRecord($recordId);
        if ((int) $record->status !== 1) {
            throw new BusinessException('该助力已结束', 40054);
        }

        $activity = $this->getActiveActivity((int) $record->activity_id);
        if ($helperUserId === (int) $record->user_id) {
            throw new BusinessException('不能为自己的助力', 40055);
        }

        // 风控防刷：黑名单拦截（object_type=5 裂变活动）
        $this->riskService->checkBlacklist($helperUserId, 5, 'ASSIST'.$recordId);

        try {
            DB::transaction(function () use ($helperUserId, $record, $activity): void {
                try {
                    $this->helperRepository->builder()->insert([
                        'record_id' => $record->id,
                        'helper_user_id' => $helperUserId,
                        'create_time' => now(),
                    ]);
                } catch (QueryException) {
                    throw new BusinessException('您已为该好友助力过', 40056);
                }

                $affected = $this->recordRepository->builder()
                    ->where('id', $record->id)
                    ->where('status', 1)
                    ->update([
                        'helper_count' => DB::raw('helper_count + 1'),
                        'updated_at' => now(),
                    ]);
                if ($affected === 0) {
                    throw new BusinessException('助力失败,该助力已结束', 40057);
                }

                $helperCount = (int) $this->recordRepository->builder()->where('id', $record->id)->value('helper_count');
                if ($helperCount >= (int) $activity->required_count) {
                    $granted = $this->recordRepository->builder()
                        ->where('id', $record->id)
                        ->where('status', 1)
                        ->update([
                            'status' => 2,
                            'finish_time' => now(),
                            'updated_at' => now(),
                        ]);
                    if ($granted > 0) {
                        $this->grantReward((int) $record->user_id, $activity, (int) $record->id);
                    }
                }
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('助力失败:'.$e->getMessage(), 40058);
        }
    }

    /**
     * 我的助力进度
     */
    public function myRecord(int $userId, int $activityId): array
    {
        $record = $this->recordRepository->builder()
            ->where('activity_id', $activityId)
            ->where('user_id', $userId)
            ->first();

        return $record === null ? [] : (array) $record;
    }

    /**
     * 过期扫描：进行中且活动已结束 → 1→3，返回过期数
     */
    public function expireDue(int $limit = 100): int
    {
        $due = $this->recordRepository->builder()
            ->join('marketing_assist_activities as a', 'a.id', '=', 'marketing_assist_records.activity_id')
            ->where('marketing_assist_records.status', 1)
            ->where('a.end_time', '<=', now())
            ->select('marketing_assist_records.id')
            ->limit($limit)
            ->get();

        $expired = 0;
        foreach ($due as $record) {
            $expired += $this->recordRepository->builder()
                ->where('id', $record->id)
                ->where('status', 1)
                ->update(['status' => 3, 'updated_at' => now()]);
        }

        return $expired;
    }

    /**
     * 发奖：券写用户券表；积分自增并落流水（biz_type=8 活动奖励）
     * 调用前提：1→2 条件更新已在同事务内成功，发奖异常整事务回滚
     */
    private function grantReward(int $userId, object $activity, int $recordId): void
    {
        if ((int) $activity->reward_type === 1) {
            // 优惠券：按券类型有效期落用户券
            $coupon = DB::table('marketing_coupons')->where('id', (int) $activity->reward_ref)->first();

            DB::table('marketing_user_coupons')->insert([
                'coupon_id' => (int) $activity->reward_ref,
                'user_id' => $userId,
                'status' => 0,
                'expire_at' => $coupon?->end_time,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        // 积分：config.points 为奖励点数
        $config = json_decode((string) $activity->config, true) ?: [];
        $points = (int) ($config['points'] ?? 0);
        if ($points <= 0) {
            return;
        }

        DB::table('users')->where('id', $userId)->increment('points', $points);
        $pointsAfter = (int) DB::table('users')->where('id', $userId)->value('points');
        $this->pointLogRepository->builder()->insert([
            'user_id' => $userId,
            'biz_type' => 8,
            'change_points' => $points,
            'points_after' => $pointsAfter,
            'biz_no' => 'ASSIST'.$recordId,
            'description' => '助力活动奖励',
            'create_time' => now(),
        ]);
    }

    private function getActiveActivity(int $activityId): object
    {
        $activity = $this->activityRepository->builder()->where('id', $activityId)->first();
        if ($activity === null) {
            throw new BusinessException('助力活动不存在', 40059);
        }
        if ((int) $activity->status !== 1) {
            throw new BusinessException('助力活动已停用', 40060);
        }
        if (now()->lt($activity->start_time) || now()->gt($activity->end_time)) {
            throw new BusinessException('助力活动不在有效期内', 40061);
        }

        return $activity;
    }

    private function getRecord(int $recordId): object
    {
        $record = $this->recordRepository->builder()->where('id', $recordId)->first();
        if ($record === null) {
            throw new BusinessException('助力记录不存在', 40062);
        }

        return $record;
    }
}
