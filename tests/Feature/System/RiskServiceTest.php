<?php

declare(strict_types=1);

namespace Tests\Feature\System;

use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Repositories\SystemRiskRuleRepository;
use App\Services\RiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class RiskServiceTest extends TestCase
{
    use RefreshDatabase;

    private RiskService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RiskService(
            new SystemRiskRuleRepository,
            new SystemRiskRecordRepository,
        );
    }

    private function createRule(int $ruleType, string $conditionExpr, int $action = 1, int $status = 1): int
    {
        return (int) DB::table('system_risk_rules')->insertGetId([
            'name' => '测试规则',
            'rule_type' => $ruleType,
            'condition_expr' => $conditionExpr,
            'action' => $action,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_blacklist_blocks_withdraw_and_writes_record(): void
    {
        $ruleId = $this->createRule(1, '{"user_ids":[7]}');

        try {
            $this->service->checkWithdraw(7, 100.00, 'W1');
            $this->fail('黑名单用户应被拦截');
        } catch (BusinessException $e) {
            $this->assertStringContainsString('风控拦截', $e->getMessage());
        }

        $this->assertDatabaseHas('system_risk_records', [
            'user_id' => 7, 'rule_id' => $ruleId, 'object_type' => 3, 'object_no' => 'W1', 'action' => 1,
        ]);
    }

    public function test_blacklist_passes_after_appeal_approved(): void
    {
        $ruleId = $this->createRule(1, '{"user_ids":[7]}');
        // 申诉通过的记录 → 解除拦截
        DB::table('system_risk_records')->insert([
            'user_id' => 7, 'rule_id' => $ruleId, 'object_type' => 3, 'object_no' => 'W0',
            'action' => 1, 'appeal_status' => 2, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->service->checkWithdraw(7, 100.00, 'W1');

        $this->assertDatabaseCount('system_risk_records', 1);
    }

    public function test_high_freq_blocks_when_count_exceeds_window(): void
    {
        $this->createRule(2, '{"max_count":1,"window_minutes":60}');
        // 窗口内已有一笔在途提现单
        DB::table('user_withdraw_orders')->insert([
            'withdraw_no' => 'WEXIST1', 'user_id' => 7, 'amount' => '20.00', 'status' => 10,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('风控拦截');

        $this->service->checkWithdraw(7, 100.00, 'W2');
    }

    public function test_high_freq_mark_action_allows_and_records(): void
    {
        $this->createRule(2, '{"max_count":1,"window_minutes":60}', 2);
        DB::table('user_withdraw_orders')->insert([
            'withdraw_no' => 'WEXIST1', 'user_id' => 7, 'amount' => '20.00', 'status' => 10,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->service->checkWithdraw(7, 100.00, 'W2');

        $this->assertDatabaseHas('system_risk_records', [
            'user_id' => 7, 'object_no' => 'W2', 'action' => 2,
        ]);
    }

    public function test_disabled_rule_ignored(): void
    {
        $this->createRule(1, '{"user_ids":[7]}', 1, 0);

        $this->service->checkWithdraw(7, 100.00, 'W1');

        $this->assertDatabaseCount('system_risk_records', 0);
    }

    public function test_appeal_handle_transitions_guarded(): void
    {
        $ruleId = $this->createRule(1, '{"user_ids":[]}');
        $recordId = (int) DB::table('system_risk_records')->insertGetId([
            'user_id' => 7, 'rule_id' => $ruleId, 'object_type' => 3, 'object_no' => 'W1',
            'action' => 1, 'appeal_status' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        // 仅申诉中(1)可处理
        $this->service->handleAppeal($recordId, true, '情况属实,解除');

        $this->assertDatabaseHas('system_risk_records', ['id' => $recordId, 'appeal_status' => 2]);

        // 已处理后再处理被守卫拒绝
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('仅申诉中的事件可处理');

        $this->service->handleAppeal($recordId, false, '重复处理');
    }
}
