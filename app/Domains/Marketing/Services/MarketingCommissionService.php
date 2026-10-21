<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionCommissionRepository;
use App\Domains\Marketing\Repositories\MarketingDistributionRelationRepository;
use App\Domains\Marketing\Repositories\MarketingDistributionUserRepository;
use App\Domains\User\Enums\UserAccountLogBizTypeEnum;
use App\Domains\User\Services\UserAccountService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;
use Throwable;

/**
 * 佣金生命周期：订单完成建待结算记录 → 保护期到期结算入账 → 退款冲销
 * 保护期内退款置失效（未动资金）；已结算退款落负额冲销记录（reversal_of_id 回指，唯一索引允许多 NULL 即幂等键）并扣回入账
 * 触发方：订单域完成/售后退款回调（本期由测试与服务调用方直调）
 */
class MarketingCommissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributionCommissionRepository $repository,
        private readonly MarketingDistributionRelationRepository $relationRepository,
        private readonly MarketingDistributionUserRepository $distributionUserRepository,
        private readonly MarketingCommissionRuleService $ruleService,
        private readonly UserAccountService $accountService,
    ) {}

    public function getRepository(): MarketingDistributionCommissionRepository
    {
        return $this->repository;
    }

    /**
     * 订单完成建待结算佣金：按下单人绑定关系快照（一级/二级）逐明细计佣，无绑定静默跳过
     *
     * @param  array<int, array{order_item_id: int, product_id: int, category_id: int, pay_amount: float}>  $items
     */
    public function createPendingOnOrderFinished(int $orderId, int $buyerId, array $items): int
    {
        $relations = $this->relationRepository->builder()
            ->where('user_id', $buyerId)
            ->orderBy('id')
            ->get();
        $level1 = $relations->firstWhere('level', 1);
        $level2 = $relations->firstWhere('level', 2);
        if ($level1 === null && $level2 === null) {
            return 0;
        }

        $protectDays = (int) $this->ruleService->settingFloat('distribution.protect_days', 7);
        $settleTime = now()->addDays($protectDays)->format('Y-m-d H:i:s');
        $inserted = 0;

        foreach ($items as $item) {
            $rates = $this->ruleService->resolveRate((int) $item['product_id'], (int) $item['category_id']);
            $baseAmount = round(floatval($item['pay_amount']), 2);

            foreach ([1 => $level1, 2 => $level2] as $level => $relation) {
                if ($relation === null) {
                    continue;
                }

                $rate = $level === 1 ? $rates['level1_rate'] : $rates['level2_rate'];
                if ($rate <= 0) {
                    continue;
                }

                $amount = round($baseAmount * $rate / 100, 2);
                $distributorId = (int) $relation->distributor_id;

                $this->repository->builder()->insert([
                    'distributor_id' => $distributorId,
                    'user_id' => $buyerId,
                    'order_id' => $orderId,
                    'order_item_id' => (int) $item['order_item_id'],
                    'level' => $level,
                    'order_amount' => number_format($baseAmount, 2, '.', ''),
                    'rate' => number_format($rate, 2, '.', ''),
                    'amount' => number_format($amount, 2, '.', ''),
                    'status' => 0,
                    'settle_time' => $settleTime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->incrementCommission($distributorId, 'total_commission', $amount);
                $inserted++;
            }
        }

        return $inserted;
    }

    /**
     * 结算到期佣金：状态 0→1 条件更新成功后入账（入账失败回滚整个事务，状态机不先行）
     */
    public function settleDue(int $limit = 100): int
    {
        $due = $this->repository->builder()
            ->where('status', 0)
            ->where('settle_time', '<=', now())
            ->whereNull('reversal_of_id')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $settled = 0;
        foreach ($due as $record) {
            try {
                DB::transaction(function () use ($record): void {
                    $affected = $this->repository->builder()
                        ->where('id', $record->id)
                        ->where('status', 0)
                        ->update([
                            'status' => 1,
                            'settled_at' => now(),
                            'updated_at' => now(),
                        ]);
                    if ($affected === 0) {
                        return; // 并发已结算，跳过
                    }

                    $this->accountService->credit(
                        (int) $record->distributor_id,
                        floatval($record->amount),
                        UserAccountLogBizTypeEnum::CommissionCredit->value,
                        'COMM'.$record->id,
                        '佣金结算入账',
                    );
                    $this->incrementCommission((int) $record->distributor_id, 'settled_commission', floatval($record->amount));
                });
                $settled++;
            } catch (Throwable) {
                // 单条失败不阻塞批次，下轮重试
            }
        }

        return $settled;
    }

    /**
     * 退款冲销：保护期内（待结算）置失效不动资金；已结算落负额冲销记录并扣回（可扣成欠款），分销员计数同步扣减
     *
     * @param  int|null  $orderItemId  空=冲销整单
     */
    public function reverseForRefund(int $orderId, ?int $orderItemId = null): int
    {
        $query = $this->repository->builder()
            ->where('order_id', $orderId)
            ->whereNull('reversal_of_id')
            ->whereIn('status', [0, 1]);
        if ($orderItemId !== null) {
            $query->where('order_item_id', $orderItemId);
        }
        $targets = $query->orderBy('id')->get();

        $reversed = 0;
        foreach ($targets as $record) {
            try {
                DB::transaction(function () use ($record): void {
                    if ((int) $record->status === 0) {
                        // 保护期内退款：0→2，未动资金
                        $this->repository->builder()
                            ->where('id', $record->id)
                            ->where('status', 0)
                            ->update(['status' => 2, 'updated_at' => now()]);

                        return;
                    }

                    // 已结算退款：先查冲销幂等（uk_reversal_of 兜底并发），再落负记录
                    $exists = $this->repository->builder()
                        ->where('reversal_of_id', $record->id)
                        ->exists();
                    if ($exists) {
                        return;
                    }

                    $amount = floatval($record->amount);
                    $reversalId = (int) $this->repository->builder()->insertGetId([
                        'distributor_id' => $record->distributor_id,
                        'user_id' => $record->user_id,
                        'order_id' => $record->order_id,
                        'order_item_id' => $record->order_item_id,
                        'level' => $record->level,
                        'order_amount' => $record->order_amount,
                        'rate' => $record->rate,
                        'amount' => number_format(-$amount, 2, '.', ''),
                        'status' => 1,
                        'settled_at' => now(),
                        'settle_time' => $record->settle_time,
                        'reversal_of_id' => $record->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->repository->builder()
                        ->where('id', $record->id)
                        ->where('status', 1)
                        ->update([
                            'status' => 3,
                            'reversal_record_id' => $reversalId,
                            'updated_at' => now(),
                        ]);

                    $this->accountService->deduct(
                        (int) $record->distributor_id,
                        $amount,
                        'COMM-'.$reversalId,
                        '佣金退款冲销扣回',
                    );
                    $this->incrementCommission((int) $record->distributor_id, 'total_commission', -$amount);
                    $this->incrementCommission((int) $record->distributor_id, 'settled_commission', -$amount);
                });
                $reversed++;
            } catch (QueryException) {
                // 撞 uk_reversal_of：并发冲销已落负记录，幂等跳过
            } catch (Throwable) {
                // 单条失败不阻塞批次
            }
        }

        return $reversed;
    }

    /**
     * 分销员佣金计数累加（负值即冲销扣减）
     */
    private function incrementCommission(int $distributorId, string $column, float $amount): void
    {
        $this->distributionUserRepository->builder()
            ->where('user_id', $distributorId)
            ->update([
                $column => DB::raw($column.' + '.number_format($amount, 2, '.', '')),
                'updated_at' => now(),
            ]);
    }
}
