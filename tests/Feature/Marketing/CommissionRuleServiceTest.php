<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Domains\Marketing\Repositories\MarketingCommissionRuleRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use App\Services\MarketingCommissionRuleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommissionRuleServiceTest extends TestCase
{
    use RefreshDatabase;

    private MarketingCommissionRuleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MarketingCommissionRuleService(
            new MarketingCommissionRuleRepository,
            new SystemSettingRepository,
        );
    }

    private function createRule(int $scopeType, int $scopeId, float $level1, float $level2, int $status = 1): void
    {
        DB::table('marketing_commission_rules')->insert([
            'scope_type' => $scopeType,
            'scope_id' => $scopeId,
            'level1_rate' => $level1,
            'level2_rate' => $level2,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createSetting(string $key, string $value): void
    {
        DB::table('system_settings')->insert([
            'group' => 'distribution', 'key' => $key, 'title' => $key, 'value' => $value, 'type' => 'number',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_product_override_wins_over_category(): void
    {
        $this->createRule(1, 3, 5.00, 2.00);
        $this->createRule(2, 5, 8.00, 4.00);

        $rates = $this->service->resolveRate(5, 3);

        $this->assertSame('product', $rates['source']);
        $this->assertSame(8.00, $rates['level1_rate']);
        $this->assertSame(4.00, $rates['level2_rate']);
    }

    public function test_category_rule_used_when_no_product_override(): void
    {
        $this->createRule(1, 3, 5.00, 2.00);

        $rates = $this->service->resolveRate(5, 3);

        $this->assertSame('category', $rates['source']);
        $this->assertSame(5.00, $rates['level1_rate']);
    }

    public function test_global_settings_fallback_when_no_rule(): void
    {
        $this->createSetting('distribution.level1_rate', '6.50');
        $this->createSetting('distribution.level2_rate', '1.50');

        $rates = $this->service->resolveRate(5, 3);

        $this->assertSame('global', $rates['source']);
        $this->assertSame(6.50, $rates['level1_rate']);
        $this->assertSame(1.50, $rates['level2_rate']);
    }

    public function test_disabled_rule_skipped_falls_through(): void
    {
        $this->createRule(2, 5, 8.00, 4.00, 0);
        $this->createRule(1, 3, 5.00, 2.00);

        $rates = $this->service->resolveRate(5, 3);

        $this->assertSame('category', $rates['source']);
    }

    public function test_rates_resolved_as_percent_decimals(): void
    {
        $this->createRule(2, 5, 7.55, 0.50);

        $rates = $this->service->resolveRate(5, 3);

        $this->assertSame(7.55, $rates['level1_rate']);
        $this->assertSame(0.50, $rates['level2_rate']);
    }
}
