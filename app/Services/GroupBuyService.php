<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Marketing\Repositories\MarketingGroupBuyActivityRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyItemRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamMemberRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Throwable;

/**
 * 拼团服务：开团/参团/成团/超时解散
 * 防线：uk(team_id,user_id) 一团一单、uk(order_no) 一单一团（双重唯一兜底刷单）；
 * member_count 与成员表同事务条件自增（where status=1 and member_count<group_size 防超员），人齐即条件更新成团；
 * 订单联动为契约模式：orderNo 由调用方（订单域下单流程）传入，本期测试直调
 */
class GroupBuyService
{
    public function __construct(
        private readonly MarketingGroupBuyActivityRepository $activityRepository,
        private readonly MarketingGroupBuyItemRepository $itemRepository,
        private readonly MarketingGroupBuyTeamRepository $teamRepository,
        private readonly MarketingGroupBuyTeamMemberRepository $memberRepository,
        private readonly SystemSettingRepository $settingRepository,
    ) {}

    /**
     * 开团：校验活动/场次商品/限购 → 事务内建团 + 团长成员记录
     */
    public function openTeam(int $userId, int $activityId, int $skuId, string $orderNo): int
    {
        $activity = $this->getActiveActivity($activityId);
        $this->assertItemExists($activityId, $skuId);
        $this->assertPerLimit($userId, $activityId, (int) $activity->per_limit);

        $expireHours = $this->settingFloat('group_buy.expire_hours', 24);

        try {
            return (int) DB::transaction(function () use ($userId, $activityId, $orderNo, $expireHours): int {
                $teamId = (int) $this->teamRepository->builder()->insertGetId([
                    'activity_id' => $activityId,
                    'leader_user_id' => $userId,
                    'status' => 1,
                    'member_count' => 1,
                    'expire_time' => now()->addHours($expireHours)->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->insertMember($teamId, $userId, $orderNo);

                return $teamId;
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('开团失败:'.$e->getMessage(), 40040);
        }
    }

    /**
     * 参团：成员记录插入（uk 兜底）→ member_count 条件自增（防超员）→ 人齐条件更新成团
     */
    public function joinTeam(int $userId, int $teamId, string $orderNo): void
    {
        $team = $this->getTeam($teamId);
        if ((int) $team->status !== 1) {
            throw new BusinessException('该团已结束', 40041);
        }
        if (strtotime((string) $team->expire_time) <= time()) {
            throw new BusinessException('该团已超时', 40042);
        }

        $activity = $this->getActiveActivity((int) $team->activity_id);
        if ((int) $team->member_count >= (int) $activity->group_size) {
            throw new BusinessException('该团已满员', 40043);
        }
        // 同团重复参团优先于限购判断，给出精确错误
        $joinedThisTeam = $this->memberRepository->builder()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->exists();
        if ($joinedThisTeam) {
            throw new BusinessException('您已参与该团', 40052);
        }
        $this->assertPerLimit($userId, (int) $team->activity_id, (int) $activity->per_limit);

        $groupSize = (int) $activity->group_size;

        try {
            DB::transaction(function () use ($userId, $teamId, $orderNo, $groupSize): void {
                $this->insertMember($teamId, $userId, $orderNo);

                $affected = $this->teamRepository->builder()
                    ->where('id', $teamId)
                    ->where('status', 1)
                    ->where('member_count', '<', $groupSize)
                    ->update([
                        'member_count' => DB::raw('member_count + 1'),
                        'updated_at' => now(),
                    ]);
                if ($affected === 0) {
                    throw new BusinessException('参团失败,该团状态已变化', 40044);
                }

                $memberCount = (int) $this->teamRepository->builder()->where('id', $teamId)->value('member_count');
                if ($memberCount >= $groupSize) {
                    $this->teamRepository->builder()
                        ->where('id', $teamId)
                        ->where('status', 1)
                        ->update([
                            'status' => 2,
                            'success_time' => now(),
                            'updated_at' => now(),
                        ]);
                }
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('参团失败:'.$e->getMessage(), 40045);
        }
    }

    /**
     * 团详情：团 + 成员列表
     */
    public function teamDetail(int $teamId): array
    {
        $team = $this->teamRepository->builder()->where('id', $teamId)->first();
        if ($team === null) {
            throw new BusinessException('团不存在', 40046);
        }

        return [
            'team' => (array) $team,
            'members' => $this->memberRepository->builder()
                ->where('team_id', $teamId)
                ->orderBy('join_time')
                ->get()
                ->map(fn ($row): array => (array) $row)
                ->all(),
        ];
    }

    /**
     * 超时解散扫描：拼团中且已过成团截止 → 1→3（条件更新），返回解散团数
     */
    public function expireDueTeams(int $limit = 100): int
    {
        $due = $this->teamRepository->builder()
            ->where('status', 1)
            ->where('expire_time', '<=', now())
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $expired = 0;
        foreach ($due as $team) {
            $affected = $this->teamRepository->builder()
                ->where('id', $team->id)
                ->where('status', 1)
                ->update(['status' => 3, 'updated_at' => now()]);
            $expired += $affected;
        }

        return $expired;
    }

    private function getActiveActivity(int $activityId): object
    {
        $activity = $this->activityRepository->builder()->where('id', $activityId)->first();
        if ($activity === null) {
            throw new BusinessException('拼团活动不存在', 40047);
        }
        if ((int) $activity->status !== 1) {
            throw new BusinessException('拼团活动已停用', 40048);
        }
        if (now()->lt($activity->start_time) || now()->gt($activity->end_time)) {
            throw new BusinessException('拼团活动不在有效期内', 40049);
        }

        return $activity;
    }

    private function assertItemExists(int $activityId, int $skuId): void
    {
        $exists = $this->itemRepository->builder()
            ->where('activity_id', $activityId)
            ->where('sku_id', $skuId)
            ->exists();
        if (! $exists) {
            throw new BusinessException('该商品未参加拼团活动', 40050);
        }
    }

    private function assertPerLimit(int $userId, int $activityId, int $perLimit): void
    {
        $joined = $this->memberRepository->builder()
            ->join('marketing_group_buy_teams as t', 't.id', '=', 'marketing_group_buy_team_members.team_id')
            ->where('t.activity_id', $activityId)
            ->where('marketing_group_buy_team_members.user_id', $userId)
            ->count();
        if ($joined >= $perLimit) {
            throw new BusinessException('已达到该活动参团上限', 40051);
        }
    }

    private function getTeam(int $teamId): object
    {
        $team = $this->teamRepository->builder()->where('id', $teamId)->first();
        if ($team === null) {
            throw new BusinessException('团不存在', 40046);
        }

        return $team;
    }

    /**
     * 成员插入：uk(team,user) 与 uk(order_no) 双重兜底，撞键转业务错误
     */
    private function insertMember(int $teamId, int $userId, string $orderNo): void
    {
        try {
            $this->memberRepository->builder()->insert([
                'team_id' => $teamId,
                'user_id' => $userId,
                'order_no' => $orderNo,
                'join_time' => now(),
                'create_time' => now(),
            ]);
        } catch (QueryException $e) {
            throw new BusinessException(str_contains($e->getMessage(), 'team_user') ? '您已参与该团' : '该订单已参与过拼团', 40052);
        }
    }

    private function settingFloat(string $key, float $default): float
    {
        $value = $this->settingRepository->builder()->where('key', $key)->value('value');

        return $value === null ? $default : floatval($value);
    }
}
