<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Repositories\SystemRiskRuleRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

/**
 * 风控服务：营销与资金动作的前置拦截检查
 * 规则条件以 condition_expr(JSON) 参数化，检查器按 rule_type 分派；
 * 命中即落 system_risk_records 事件（拦截可追溯），action=2 标记放行留痕
 */
class RiskService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemRiskRuleRepository $ruleRepository,
        private readonly SystemRiskRecordRepository $recordRepository,
    ) {}

    public function getRepository(): SystemRiskRuleRepository
    {
        return $this->ruleRepository;
    }

    /**
     * 提现前置检查：命中拦截型规则抛 BusinessException，标记型规则放行但留痕
     */
    public function checkWithdraw(int $userId, float $amount, string $objectNo): void
    {
        $rules = $this->ruleRepository->builder()
            ->where('status', 1)
            ->whereIn('rule_type', [1, 2])
            ->orderBy('id')
            ->get();

        foreach ($rules as $rule) {
            if (! $this->evaluateRule($rule, $userId, $amount)) {
                continue;
            }

            $this->record($userId, (int) $rule->id, 3, $objectNo, (int) $rule->action, sprintf(
                '规则[%s]参数=%s,提现金额=%.2f',
                $rule->name,
                $rule->condition_expr,
                $amount,
            ));

            if ((int) $rule->action === 1) {
                throw new BusinessException('触发风控拦截:'.$rule->name, 40010);
            }
        }
    }

    /**
     * 落风控事件（objectType:1=订单,2=优惠券,3=提现,4=售后）
     */
    public function record(int $userId, int $ruleId, int $objectType, string $objectNo, int $action, string $remark): int
    {
        return (int) $this->recordRepository->builder()->insertGetId([
            'user_id' => $userId,
            'rule_id' => $ruleId,
            'object_type' => $objectType,
            'object_no' => $objectNo,
            'action' => $action,
            'remark' => $remark,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * 申诉处理：仅申诉中(1)可流转为通过(2)/驳回(3)，黑名单解除拦截即依赖申诉通过记录
     */
    public function handleAppeal(int $recordId, bool $pass, string $handleNote): void
    {
        $target = $pass ? 2 : 3;
        $affected = $this->recordRepository->builder()
            ->where('id', $recordId)
            ->where('appeal_status', 1)
            ->update([
                'appeal_status' => $target,
                'handle_note' => $handleNote,
                'handled_at' => now(),
                'updated_at' => now(),
            ]);

        if ($affected === 0) {
            throw new BusinessException('仅申诉中的事件可处理', 40011);
        }
    }

    /**
     * 规则评估：按 rule_type 分派检查器（3=异常领券/4=佣金套利/5=休眠分级 本期占位恒不命中）
     */
    private function evaluateRule(object $rule, int $userId, float $amount): bool
    {
        $params = json_decode((string) $rule->condition_expr, true);
        if (! is_array($params)) {
            return false;
        }

        return match ((int) $rule->rule_type) {
            1 => $this->evaluateBlacklist($rule, $userId, $params),
            2 => $this->evaluateWithdrawHighFreq($userId, $params),
            default => false,
        };
    }

    /**
     * 黑名单：命中名单但存在申诉通过记录则放行（申诉通过即解除拦截）
     */
    private function evaluateBlacklist(object $rule, int $userId, array $params): bool
    {
        $userIds = array_map('intval', $params['user_ids'] ?? []);
        if (! in_array($userId, $userIds, true)) {
            return false;
        }

        $appealed = $this->recordRepository->builder()
            ->where('rule_id', $rule->id)
            ->where('user_id', $userId)
            ->where('appeal_status', 2)
            ->exists();

        return ! $appealed;
    }

    /**
     * 提现高频：统计窗口内在途提现单数（申请中/审核通过/打款中）达到阈值
     */
    private function evaluateWithdrawHighFreq(int $userId, array $params): bool
    {
        $maxCount = (int) ($params['max_count'] ?? 0);
        $windowMinutes = (int) ($params['window_minutes'] ?? 0);
        if ($maxCount <= 0 || $windowMinutes <= 0) {
            return false;
        }

        $count = DB::table('user_withdraw_orders')
            ->where('user_id', $userId)
            ->whereIn('status', [10, 20, 30])
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();

        return $count >= $maxCount;
    }
}
