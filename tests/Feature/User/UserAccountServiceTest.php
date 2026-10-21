<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Domains\User\Enums\UserAccountLogBizTypeEnum;
use App\Domains\User\Repositories\UserAccountLogRepository;
use App\Domains\User\Repositories\UserAccountRepository;
use App\Services\UserAccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class UserAccountServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserAccountService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new UserAccountService(
            new UserAccountRepository,
            new UserAccountLogRepository,
        );
    }

    public function test_credit_increases_balance_and_writes_snapshot_log(): void
    {
        $this->service->credit(1, 100.00, UserAccountLogBizTypeEnum::BizType9->value, 'R1', '充值入账');

        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '100.00', 'frozen' => '0.00']);
        $this->assertDatabaseHas('user_account_logs', [
            'user_id' => 1,
            'biz_type' => 9,
            'amount' => '100.00',
            'balance_after' => '100.00',
            'frozen_after' => '0.00',
            'biz_no' => 'R1',
        ]);
    }

    public function test_freeze_rejects_when_available_insufficient(): void
    {
        $this->service->credit(1, 50.00, UserAccountLogBizTypeEnum::BizType9->value);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('可用余额不足');

        $this->service->freeze(1, 100.00, UserAccountLogBizTypeEnum::BizType2->value, 'W1');
    }

    public function test_freeze_rejects_when_balance_negative_overdraft(): void
    {
        $this->service->credit(1, 10.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->deduct(1, 30.00);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('可用余额不足');

        $this->service->freeze(1, 10.00, UserAccountLogBizTypeEnum::BizType2->value, 'W1');
    }

    public function test_unfreeze_rejects_when_frozen_insufficient(): void
    {
        $this->service->credit(1, 50.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->freeze(1, 20.00, UserAccountLogBizTypeEnum::BizType2->value, 'W1');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('冻结金额不足');

        $this->service->unfreeze(1, 30.00, UserAccountLogBizTypeEnum::BizType4->value, 'W1');
    }

    public function test_deduct_allows_balance_go_negative(): void
    {
        $this->service->credit(1, 10.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->deduct(1, 30.00);

        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '-20.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 5, 'amount' => '-30.00', 'balance_after' => '-20.00']);
    }

    public function test_settle_frozen_requires_both_balance_and_frozen_guard(): void
    {
        // 提现在途（frozen=30）期间冲销扣回把余额扣空，settleFrozen 双守卫应失败，走 unfreeze 回退
        $this->service->credit(1, 50.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->freeze(1, 30.00, UserAccountLogBizTypeEnum::BizType2->value, 'W1');
        $this->service->deduct(1, 50.00);

        try {
            $this->service->settleFrozen(1, 30.00, 'W1');
            $this->fail('双守卫应拦截余额不足的核销');
        } catch (BusinessException) {
            $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '0.00', 'frozen' => '30.00']);
        }

        $this->service->unfreeze(1, 30.00, UserAccountLogBizTypeEnum::BizType4->value, 'W1');
        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '0.00', 'frozen' => '0.00']);
    }

    public function test_repeated_freeze_beyond_available_is_rejected_by_condition_update(): void
    {
        $this->service->credit(1, 100.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->freeze(1, 80.00, UserAccountLogBizTypeEnum::BizType2->value, 'W1');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('可用余额不足');

        $this->service->freeze(1, 30.00, UserAccountLogBizTypeEnum::BizType2->value, 'W2');
    }

    public function test_adjust_accepts_negative_amount(): void
    {
        $this->service->credit(1, 50.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->adjust(1, -20.00, '', '纠错调整');

        $this->assertDatabaseHas('user_accounts', ['user_id' => 1, 'balance' => '30.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 10, 'amount' => '-20.00', 'balance_after' => '30.00']);
    }

    public function test_logs_amount_sum_equals_balance(): void
    {
        $this->service->credit(1, 100.00, UserAccountLogBizTypeEnum::BizType9->value);
        $this->service->deduct(1, 30.00);
        $this->service->adjust(1, 5.50);

        $sum = floatval(DB::table('user_account_logs')->where('user_id', 1)->sum('amount'));
        $balance = floatval(DB::table('user_accounts')->where('user_id', 1)->value('balance'));

        $this->assertSame($balance, round($sum, 2));
    }
}
