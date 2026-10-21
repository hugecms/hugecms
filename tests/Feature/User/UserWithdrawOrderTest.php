<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Repositories\SystemRiskRuleRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use App\Domains\User\Enums\UserAccountLogBizTypeEnum;
use App\Domains\User\Repositories\UserAccountLogRepository;
use App\Domains\User\Repositories\UserAccountRepository;
use App\Domains\User\Repositories\UserWithdrawOrderRepository;
use App\Plugins\Pay\PayService;
use App\Services\RiskService;
use App\Services\UserAccountService;
use App\Services\UserWithdrawOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class UserWithdrawOrderTest extends TestCase
{
    use RefreshDatabase;

    private UserAccountService $accountService;

    private UserWithdrawOrderService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountService = new UserAccountService(
            new UserAccountRepository,
            new UserAccountLogRepository,
        );
        $this->service = new UserWithdrawOrderService(
            new UserWithdrawOrderRepository,
            $this->accountService,
            new RiskService(new SystemRiskRuleRepository, new SystemRiskRecordRepository),
            new SystemSettingRepository,
            new PayService,
        );
    }

    /**
     * 造一个有余额的用户账户
     */
    private function fundedUser(float $amount): int
    {
        $this->accountService->credit(1, $amount, UserAccountLogBizTypeEnum::BizType9->value);

        return 1;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'amount' => '20.00',
            'accountType' => 'bank',
            'accountName' => '张三',
            'accountNo' => '6222000011112222',
            'userNote' => '',
        ], $overrides);
    }

    public function test_apply_freezes_balance_and_creates_pending_order(): void
    {
        $this->fundedUser(100.00);

        $id = $this->service->apply(1, $this->payload());

        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 10, 'amount' => '20.00']);
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '100.00', 'frozen' => '20.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 2, 'biz_type' => 2, 'amount' => '0.00', 'frozen_after' => '20.00']);
    }

    public function test_apply_rejects_below_min_amount(): void
    {
        $this->fundedUser(100.00);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('提现金额不能低于');

        $this->service->apply(1, $this->payload(['amount' => '5.00']));
    }

    public function test_apply_rejects_exceeds_daily_limit(): void
    {
        $this->fundedUser(100.00);
        $this->service->apply(1, $this->payload());

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('今日提现申请次数已达上限');

        $this->service->apply(1, $this->payload());
    }

    public function test_apply_blocked_by_blacklist_rule(): void
    {
        $this->fundedUser(100.00);
        DB::table('system_risk_rules')->insert([
            'name' => '用户黑名单', 'rule_type' => 1, 'condition_expr' => '{"user_ids":[1]}', 'action' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        try {
            $this->service->apply(1, $this->payload());
            $this->fail('黑名单应拦截提现申请');
        } catch (BusinessException $e) {
            $this->assertStringContainsString('风控拦截', $e->getMessage());
        }

        $this->assertDatabaseCount('user_withdraw_orders', 0);
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'frozen' => '0.00']);
        $this->assertDatabaseCount('system_risk_records', 1);
    }

    public function test_apply_flagged_by_high_freq_but_allowed_with_record(): void
    {
        $this->fundedUser(200.00);
        DB::table('system_risk_rules')->insert([
            'name' => '提现高频拦截', 'rule_type' => 2, 'condition_expr' => '{"max_count":1,"window_minutes":60}', 'action' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('system_settings')->insert([
            'group' => 'funds', 'key' => 'withdraw.daily_limit', 'title' => '每日提现申请次数上限', 'value' => '5', 'type' => 'number',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->service->apply(1, $this->payload());
        $id = $this->service->apply(1, $this->payload());

        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 10]);
        $this->assertDatabaseCount('system_risk_records', 1);
    }

    public function test_full_path_audit_pass_pay_success(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());

        $this->service->audit($id, true, '资料齐全');
        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 20]);
        $this->assertDatabaseHas('user_accounts', ['frozen' => '20.00']);

        $this->service->markPaying($id);
        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 30]);

        $this->service->markSuccess($id, 'WXTRANSFER001');

        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 40, 'channel_order_no' => 'WXTRANSFER001']);
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '80.00', 'frozen' => '0.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 3, 'amount' => '-20.00', 'balance_after' => '80.00', 'frozen_after' => '0.00']);
    }

    public function test_audit_reject_refunds_frozen_to_available(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());

        $this->service->audit($id, false, '收款信息有误');

        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 50]);
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '100.00', 'frozen' => '0.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 4, 'amount' => '0.00', 'frozen_after' => '0.00']);
    }

    public function test_audit_rejects_when_not_pending(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());
        $this->service->audit($id, true);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('当前状态不可审核');

        $this->service->audit($id, false, '二次审核');
    }

    public function test_mark_success_rejects_when_not_paying(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('当前状态不可标记成功');

        $this->service->markSuccess($id, 'WX001');
    }

    public function test_mark_success_idempotent_replay_same_channel_order_no(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());
        $this->service->audit($id, true);
        $this->service->markPaying($id);
        $this->service->markSuccess($id, 'WXTRANSFER001');

        // 回调重放：同单同渠道单号幂等返回，不产生第二次核销（流水=充值1+冻结1+核销1）
        $this->service->markSuccess($id, 'WXTRANSFER001');

        $this->assertDatabaseCount('user_account_logs', 3);
    }

    public function test_duplicate_channel_order_no_rejected_by_unique_key(): void
    {
        $this->fundedUser(200.00);
        DB::table('system_settings')->insert([
            'group' => 'funds', 'key' => 'withdraw.daily_limit', 'title' => '每日提现申请次数上限', 'value' => '5', 'type' => 'number',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $first = $this->service->apply(1, $this->payload());
        $this->service->audit($first, true);
        $this->service->markPaying($first);
        $this->service->markSuccess($first, 'WXTRANSFER001');

        $second = $this->service->apply(1, $this->payload());
        $this->service->audit($second, true);
        $this->service->markPaying($second);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('渠道打款单号已被其他提现单使用');

        $this->service->markSuccess($second, 'WXTRANSFER001');
    }

    public function test_mark_fail_refunds_frozen_and_records_reason(): void
    {
        $this->fundedUser(100.00);
        $id = $this->service->apply(1, $this->payload());
        $this->service->audit($id, true);
        $this->service->markPaying($id);

        $this->service->markFail($id, '收款账号异常');

        $this->assertDatabaseHas('user_withdraw_orders', ['id' => $id, 'status' => 60, 'fail_reason' => '收款账号异常']);
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '100.00', 'frozen' => '0.00']);
    }
}
