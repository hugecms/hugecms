<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Domains\Marketing\Repositories\MarketingCommissionRuleRepository;
use App\Domains\Marketing\Repositories\MarketingDistributionCommissionRepository;
use App\Domains\Marketing\Repositories\MarketingDistributionRelationRepository;
use App\Domains\Marketing\Repositories\MarketingDistributionUserRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use App\Domains\User\Repositories\UserAccountLogRepository;
use App\Domains\User\Repositories\UserAccountRepository;
use App\Services\MarketingCommissionRuleService;
use App\Services\MarketingCommissionService;
use App\Services\UserAccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MarketingCommissionServiceTest extends TestCase
{
    use RefreshDatabase;

    private const int BUYER = 1;

    private const int LEVEL1_DISTRIBUTOR = 10;

    private const int LEVEL2_DISTRIBUTOR = 20;

    private MarketingCommissionService $service;

    private UserAccountService $accountService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountService = new UserAccountService(
            new UserAccountRepository,
            new UserAccountLogRepository,
        );
        $this->service = new MarketingCommissionService(
            new MarketingDistributionCommissionRepository,
            new MarketingDistributionRelationRepository,
            new MarketingDistributionUserRepository,
            new MarketingCommissionRuleService(
                new MarketingCommissionRuleRepository,
                new SystemSettingRepository,
            ),
            $this->accountService,
        );
    }

    /**
     * 基础数据：两级分销员 + 绑定关系快照 + 一笔 100 元订单明细（全局比例 5%/2%）
     */
    private function seedTwoLevelChain(): void
    {
        foreach ([self::LEVEL1_DISTRIBUTOR, self::LEVEL2_DISTRIBUTOR] as $distributorId) {
            DB::table('marketing_distribution_users')->insert([
                'user_id' => $distributorId,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ([[self::LEVEL1_DISTRIBUTOR, 1], [self::LEVEL2_DISTRIBUTOR, 2]] as [$distributorId, $level]) {
            DB::table('marketing_distribution_relations')->insert([
                'user_id' => self::BUYER,
                'distributor_id' => $distributorId,
                'level' => $level,
                'create_time' => now(),
            ]);
        }
    }

    /**
     * @return array<int, array{order_item_id: int, product_id: int, category_id: int, pay_amount: float}>
     */
    private function oneItem(float $payAmount = 100.00): array
    {
        return [['order_item_id' => 101, 'product_id' => 5, 'category_id' => 3, 'pay_amount' => $payAmount]];
    }

    public function test_create_pending_two_levels_from_relations_snapshot(): void
    {
        $this->seedTwoLevelChain();

        $inserted = $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());

        $this->assertSame(2, $inserted);
        $this->assertDatabaseHas('marketing_distribution_commissions', [
            'distributor_id' => self::LEVEL1_DISTRIBUTOR, 'level' => 1, 'amount' => '5.00', 'status' => 0,
        ]);
        $this->assertDatabaseHas('marketing_distribution_commissions', [
            'distributor_id' => self::LEVEL2_DISTRIBUTOR, 'level' => 2, 'amount' => '2.00', 'status' => 0,
        ]);
        $this->assertDatabaseHas('marketing_distribution_users', [
            'user_id' => self::LEVEL1_DISTRIBUTOR, 'total_commission' => '5.00',
        ]);
    }

    public function test_create_pending_skips_user_without_relation(): void
    {
        $inserted = $this->service->createPendingOnOrderFinished(1, 999, $this->oneItem());

        $this->assertSame(0, $inserted);
        $this->assertDatabaseCount('marketing_distribution_commissions', 0);
    }

    public function test_settle_due_credits_after_protect_period(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());
        DB::table('marketing_distribution_commissions')->update(['settle_time' => now()->subMinute()]);

        $settled = $this->service->settleDue();

        $this->assertSame(2, $settled);
        $this->assertDatabaseHas('user_accounts', ['user_id' => self::LEVEL1_DISTRIBUTOR, 'balance' => '5.00']);
        $this->assertDatabaseHas('user_accounts', ['user_id' => self::LEVEL2_DISTRIBUTOR, 'balance' => '2.00']);
        $this->assertDatabaseHas('marketing_distribution_commissions', [
            'distributor_id' => self::LEVEL1_DISTRIBUTOR, 'status' => 1,
        ]);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 1, 'amount' => '5.00']);
        $this->assertDatabaseHas('marketing_distribution_users', [
            'user_id' => self::LEVEL1_DISTRIBUTOR, 'settled_commission' => '5.00',
        ]);
    }

    public function test_settle_skips_before_due_time(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());

        $settled = $this->service->settleDue();

        $this->assertSame(0, $settled);
        $this->assertDatabaseCount('user_accounts', 0);
    }

    public function test_reverse_pending_marks_invalid_without_fund_change(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());

        $reversed = $this->service->reverseForRefund(1);

        $this->assertSame(2, $reversed);
        $this->assertDatabaseHas('marketing_distribution_commissions', [
            'distributor_id' => self::LEVEL1_DISTRIBUTOR, 'status' => 2,
        ]);
        $this->assertDatabaseCount('user_accounts', 0);
        $this->assertDatabaseCount('user_account_logs', 0);
    }

    public function test_reverse_settled_creates_paired_negative_record(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());
        DB::table('marketing_distribution_commissions')->update(['settle_time' => now()->subMinute()]);
        $this->service->settleDue();

        $reversed = $this->service->reverseForRefund(1);

        $this->assertSame(2, $reversed);

        // 原记录侧：状态 3 + 回指冲销记录
        $original = DB::table('marketing_distribution_commissions')
            ->where('distributor_id', self::LEVEL1_DISTRIBUTOR)->whereNull('reversal_of_id')->first();
        $this->assertSame(3, (int) $original->status);
        $this->assertGreaterThan(0, (int) $original->reversal_record_id);

        // 冲销记录侧：负额 + 回指原记录
        $reversal = DB::table('marketing_distribution_commissions')
            ->where('reversal_of_id', $original->id)->first();
        $this->assertNotNull($reversal);
        $this->assertSame(-5.0, floatval($reversal->amount));

        // 资金扣回：余额归零，欠款语义允许
        $this->assertDatabaseHas('user_accounts', ['user_id' => self::LEVEL1_DISTRIBUTOR, 'balance' => '0.00']);
        $this->assertDatabaseHas('user_account_logs', ['biz_type' => 5, 'amount' => '-5.00']);

        // 分销员计数双扣：可提现 = total - settled 不虚高
        $distributor = DB::table('marketing_distribution_users')
            ->where('user_id', self::LEVEL1_DISTRIBUTOR)->first();
        $this->assertSame(0.0, floatval($distributor->total_commission));
        $this->assertSame(0.0, floatval($distributor->settled_commission));
    }

    public function test_reverse_twice_is_idempotent_by_unique_key(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());
        DB::table('marketing_distribution_commissions')->update(['settle_time' => now()->subMinute()]);
        $this->service->settleDue();

        $this->service->reverseForRefund(1);
        $this->service->reverseForRefund(1);

        // 冲销记录只有一對，资金只扣一次
        $this->assertDatabaseCount('marketing_distribution_commissions', 4);
        $this->assertDatabaseHas('user_accounts', ['user_id' => self::LEVEL1_DISTRIBUTOR, 'balance' => '0.00']);
    }

    public function test_reverse_after_settle_can_overdraft_balance(): void
    {
        $this->seedTwoLevelChain();
        $this->service->createPendingOnOrderFinished(1, self::BUYER, $this->oneItem());
        DB::table('marketing_distribution_commissions')->update(['settle_time' => now()->subMinute()]);
        $this->service->settleDue();

        // 分销员已提现（模拟：人工扣走佣金），退款冲销后余额为负 = 欠款
        DB::table('user_accounts')->where('user_id', self::LEVEL1_DISTRIBUTOR)
            ->update(['balance' => DB::raw('balance - 5.00')]);

        $this->service->reverseForRefund(1);

        $this->assertDatabaseHas('user_accounts', ['user_id' => self::LEVEL1_DISTRIBUTOR, 'balance' => '-5.00']);
    }
}
