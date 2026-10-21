<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Marketing\Repositories\MarketingBargainActivityRepository;
use App\Domains\Marketing\Repositories\MarketingBargainHelperRepository;
use App\Domains\Marketing\Repositories\MarketingBargainItemRepository;
use App\Domains\Marketing\Repositories\MarketingBargainRecordRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Throwable;

/**
 * 砍价服务：发起/帮砍/下单回填/超时
 * 防线：current_price 每刀条件更新（where current_price - cut >= floor_price，金额内联字面量防 sqlite REAL<TEXT 陷阱）
 * 防并发超砍；uk(record,helper) 一人一刀只追加；uk(order_no) NULL 不参与约束（未下单互不冲突，下单回填后防一单多砍）；
 * 帮砍入口挂风控黑名单检查（object_type=5 裂变活动）
 */
class BargainService
{
    public function __construct(
        private readonly MarketingBargainActivityRepository $activityRepository,
        private readonly MarketingBargainItemRepository $itemRepository,
        private readonly MarketingBargainRecordRepository $recordRepository,
        private readonly MarketingBargainHelperRepository $helperRepository,
        private readonly SystemSettingRepository $settingRepository,
        private readonly RiskService $riskService,
    ) {}

    /**
     * 发起砍价：bargain_no 全局唯一，当前价 = 起始价
     */
    public function initiate(int $userId, int $itemId): int
    {
        $item = $this->getItem($itemId);
        $this->getActiveActivity((int) $item->activity_id);

        $expireHours = $this->settingFloat('bargain.expire_hours', 24);

        try {
            return (int) $this->recordRepository->builder()->insertGetId([
                'bargain_no' => $this->generateBargainNo(),
                'item_id' => $itemId,
                'user_id' => $userId,
                'current_price' => $item->original_price,
                'cut_count' => 0,
                'status' => 1,
                'expire_time' => now()->addHours($expireHours)->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException $e) {
            throw new BusinessException('发起砍价失败:'.(str_contains($e->getMessage(), 'bargain_no') ? '单号冲突,请重试' : $e->getMessage()), 40063);
        }
    }

    /**
     * 帮砍：helper 插入（uk 兜底一人一刀）→ current_price 条件更新（防并发超砍）→ 到底自动 1→2
     *
     * @return array{current_price: float, cut_amount: float, reached_floor: bool}
     */
    public function cut(int $helperUserId, int $recordId): array
    {
        $record = $this->getRecord($recordId);
        if ((int) $record->status !== 1) {
            throw new BusinessException('该砍价已结束', 40064);
        }
        if (strtotime((string) $record->expire_time) <= time()) {
            throw new BusinessException('该砍价已超时', 40065);
        }

        $item = $this->getItem((int) $record->item_id);
        $this->getActiveActivity((int) $item->activity_id);
        if ($helperUserId === (int) $record->user_id) {
            throw new BusinessException('不能为自己砍价', 40066);
        }

        // 风控防刷：黑名单拦截（object_type=5 裂变活动）
        $this->riskService->checkBlacklist($helperUserId, 5, (string) $record->bargain_no);

        $floorPrice = floatval($item->floor_price);
        $cutAmount = $this->calcCutAmount($record, $item);

        try {
            return DB::transaction(function () use ($helperUserId, $record, $cutAmount, $floorPrice): array {
                try {
                    $this->helperRepository->builder()->insert([
                        'record_id' => $record->id,
                        'helper_user_id' => $helperUserId,
                        'cut_amount' => number_format($cutAmount, 2, '.', ''),
                        'create_time' => now(),
                    ]);
                } catch (QueryException) {
                    throw new BusinessException('您已为该好友砍过价', 40067);
                }

                // 条件更新防并发超砍：仅当 current_price 够减时生效（金额内联字面量，跨 MySQL/sqlite 一致）
                $newPrice = round(floatval($record->current_price) - $cutAmount, 2);
                $affected = $this->recordRepository->builder()
                    ->where('id', $record->id)
                    ->where('status', 1)
                    ->whereRaw('current_price >= '.number_format($floorPrice + $cutAmount, 2, '.', ''))
                    ->update([
                        'current_price' => DB::raw('current_price - '.number_format($cutAmount, 2, '.', '')),
                        'cut_count' => DB::raw('cut_count + 1'),
                        'updated_at' => now(),
                    ]);
                if ($affected === 0) {
                    throw new BusinessException('帮砍失败,砍价状态已变化', 40068);
                }

                $currentPrice = floatval($this->recordRepository->builder()->where('id', $record->id)->value('current_price'));
                $reachedFloor = $currentPrice <= $floorPrice + 0.001;
                if ($reachedFloor) {
                    if ($currentPrice < $floorPrice) {
                        // 收敛到底价（浮点余差修正）
                        $this->recordRepository->builder()->where('id', $record->id)->update([
                            'current_price' => number_format($floorPrice, 2, '.', ''),
                        ]);
                        $currentPrice = $floorPrice;
                    }

                    $this->recordRepository->builder()
                        ->where('id', $record->id)
                        ->where('status', 1)
                        ->update([
                            'status' => 2,
                            'success_time' => now(),
                            'updated_at' => now(),
                        ]);
                }

                return [
                    'current_price' => $currentPrice,
                    'cut_amount' => $cutAmount,
                    'reached_floor' => $reachedFloor,
                ];
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('帮砍失败:'.$e->getMessage(), 40069);
        }
    }

    /**
     * 到底价后下单回填：守卫 2→3 + uk(order_no) 兜底；超时/取消（4/5）拒绝
     */
    public function markOrdered(int $userId, int $recordId, string $orderNo): void
    {
        $record = $this->getRecord($recordId);
        if ((int) $record->user_id !== $userId) {
            throw new BusinessException('仅发起人可下单', 40070);
        }
        if ((int) $record->status !== 2) {
            throw new BusinessException('当前状态不可下单', 40071);
        }

        try {
            $affected = $this->recordRepository->builder()
                ->where('id', $recordId)
                ->where('status', 2)
                ->update([
                    'status' => 3,
                    'order_no' => $orderNo,
                    'updated_at' => now(),
                ]);
            if ($affected === 0) {
                throw new BusinessException('当前状态不可下单', 40071);
            }
        } catch (QueryException) {
            throw new BusinessException('该订单号已使用', 40072);
        }
    }

    /**
     * 砍价单详情
     */
    public function detail(int $recordId): array
    {
        $record = $this->getRecord($recordId);

        return [
            'record' => (array) $record,
            'helpers' => $this->helperRepository->builder()
                ->where('record_id', $recordId)
                ->orderBy('create_time')
                ->get()
                ->map(fn ($row): array => (array) $row)
                ->all(),
        ];
    }

    /**
     * 超时扫描：砍价中/到底待下单且已过截止 → 4（超时失败，不可再下单），返回失效数
     */
    public function expireDue(int $limit = 100): int
    {
        $due = $this->recordRepository->builder()
            ->whereIn('status', [1, 2])
            ->where('expire_time', '<=', now())
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $expired = 0;
        foreach ($due as $record) {
            $expired += $this->recordRepository->builder()
                ->where('id', $record->id)
                ->whereIn('status', [1, 2])
                ->update(['status' => 4, 'updated_at' => now()]);
        }

        return $expired;
    }

    /**
     * 本刀金额：随机递减收敛到底价；最后一刀（达到 max_cut_count）直接砍到剩余空间
     */
    private function calcCutAmount(object $record, object $item): float
    {
        $current = floatval($record->current_price);
        $floor = floatval($item->floor_price);
        $remaining = round($current - $floor, 2);
        if ($remaining <= 0) {
            return 0.0;
        }

        $maxCut = (int) $item->max_cut_count;
        $cutCount = (int) $record->cut_count;
        if ($maxCut > 0 && $cutCount + 1 >= $maxCut) {
            return $remaining;
        }

        $remainingCuts = $maxCut > 0
            ? max(1, $maxCut - $cutCount)
            : max(1, (int) ceil($remaining));

        $cut = round($remaining * (rand(50, 100) / 100) / $remainingCuts, 2);

        return max(0.01, min($cut, $remaining));
    }

    private function getItem(int $itemId): object
    {
        $item = $this->itemRepository->builder()->where('id', $itemId)->first();
        if ($item === null) {
            throw new BusinessException('砍价商品不存在', 40073);
        }

        return $item;
    }

    private function getActiveActivity(int $activityId): object
    {
        $activity = $this->activityRepository->builder()->where('id', $activityId)->first();
        if ($activity === null) {
            throw new BusinessException('砍价活动不存在', 40074);
        }
        if ((int) $activity->status !== 1) {
            throw new BusinessException('砍价活动已停用', 40075);
        }
        if (now()->lt($activity->start_time) || now()->gt($activity->end_time)) {
            throw new BusinessException('砍价活动不在有效期内', 40076);
        }

        return $activity;
    }

    private function getRecord(int $recordId): object
    {
        $record = $this->recordRepository->builder()->where('id', $recordId)->first();
        if ($record === null) {
            throw new BusinessException('砍价单不存在', 40077);
        }

        return $record;
    }

    /**
     * 单号：B + 时间戳 + 随机后缀，撞唯一键重试一次
     */
    private function generateBargainNo(): string
    {
        for ($i = 0; $i < 2; $i++) {
            $no = 'B'.date('YmdHis').strtoupper(substr(uniqid(), -6));
            if (! $this->recordRepository->builder()->where('bargain_no', $no)->exists()) {
                return $no;
            }
        }

        throw new BusinessException('砍价单号生成失败,请重试', 40078);
    }

    private function settingFloat(string $key, float $default): float
    {
        $value = $this->settingRepository->builder()->where('key', $key)->value('value');

        return $value === null ? $default : floatval($value);
    }
}
