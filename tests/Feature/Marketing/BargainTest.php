<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Domains\Marketing\Repositories\MarketingBargainActivityRepository;
use App\Domains\Marketing\Repositories\MarketingBargainHelperRepository;
use App\Domains\Marketing\Repositories\MarketingBargainItemRepository;
use App\Domains\Marketing\Repositories\MarketingBargainRecordRepository;
use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Repositories\SystemRiskRuleRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use App\Services\BargainService;
use App\Services\RiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class BargainTest extends TestCase
{
    use RefreshDatabase;

    private const float ORIGINAL = 100.00;

    private const float FLOOR = 10.00;

    private BargainService $service;

    private int $itemId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new BargainService(
            new MarketingBargainActivityRepository,
            new MarketingBargainItemRepository,
            new MarketingBargainRecordRepository,
            new MarketingBargainHelperRepository,
            new SystemSettingRepository,
            new RiskService(new SystemRiskRuleRepository, new SystemRiskRecordRepository),
        );

        $activityId = (int) DB::table('marketing_bargain_activities')->insertGetId([
            'name' => '砍价测试',
            'spu_id' => 1,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDay(),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->itemId = (int) DB::table('marketing_bargain_items')->insertGetId([
            'activity_id' => $activityId,
            'sku_id' => 21,
            'original_price' => (string) self::ORIGINAL,
            'floor_price' => (string) self::FLOOR,
            'max_cut_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function initiateFor(int $userId): int
    {
        return $this->service->initiate($userId, $this->itemId);
    }

    public function test_initiate_starts_at_original_price(): void
    {
        $recordId = $this->initiateFor(1);

        $this->assertDatabaseHas('marketing_bargain_records', [
            'id' => $recordId, 'status' => 1, 'current_price' => '100.00', 'cut_count' => 0,
        ]);
    }

    public function test_cuts_decrease_price_without_crossing_floor(): void
    {
        $recordId = $this->initiateFor(1);

        $floor = self::FLOOR;
        $reached = false;
        for ($i = 0; $i < 500 && ! $reached; $i++) {
            $result = $this->service->cut(100 + $i, $recordId);
            $this->assertTrue($result['current_price'] >= $floor - 0.001, '第'.$i.'刀跌破底价');
            $reached = $result['reached_floor'];
        }

        $this->assertTrue($reached, '随机递减应在有限刀内收敛到底价');
        $this->assertDatabaseHas('marketing_bargain_records', ['id' => $recordId, 'status' => 2]);
    }

    public function test_cut_reaches_floor_marks_ready(): void
    {
        // max_cut_count=2：第一刀随机、第二刀直接砍到底
        DB::table('marketing_bargain_items')->where('id', $this->itemId)->update(['max_cut_count' => 2]);
        $recordId = $this->initiateFor(1);

        $this->service->cut(10, $recordId);
        $result = $this->service->cut(11, $recordId);

        $this->assertTrue($result['reached_floor']);
        $this->assertDatabaseHas('marketing_bargain_records', [
            'id' => $recordId, 'status' => 2, 'current_price' => '10.00',
        ]);
    }

    public function test_cut_rejects_duplicate_helper(): void
    {
        $recordId = $this->initiateFor(1);
        $this->service->cut(10, $recordId);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已为该好友砍过价');

        $this->service->cut(10, $recordId);
    }

    public function test_cut_rejects_self(): void
    {
        $recordId = $this->initiateFor(1);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('不能为自己砍价');

        $this->service->cut(1, $recordId);
    }

    public function test_mark_ordered_after_floor(): void
    {
        $recordId = $this->initiateFor(1);
        $this->forceFloor($recordId);

        $this->service->markOrdered(1, $recordId, 'ORD100');

        $this->assertDatabaseHas('marketing_bargain_records', [
            'id' => $recordId, 'status' => 3, 'order_no' => 'ORD100',
        ]);
    }

    public function test_mark_ordered_rejects_before_floor(): void
    {
        $recordId = $this->initiateFor(1);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('当前状态不可下单');

        $this->service->markOrdered(1, $recordId, 'ORD100');
    }

    public function test_mark_ordered_rejects_reused_order_no(): void
    {
        $first = $this->initiateFor(1);
        $this->forceFloor($first);
        $this->service->markOrdered(1, $first, 'ORD100');

        $second = $this->initiateFor(1);
        $this->forceFloor($second);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('该订单号已使用');

        $this->service->markOrdered(1, $second, 'ORD100');
    }

    public function test_expire_due_marks_failed(): void
    {
        $recordId = $this->initiateFor(1);
        DB::table('marketing_bargain_records')->where('id', $recordId)
            ->update(['expire_time' => now()->subMinute()]);

        $expired = $this->service->expireDue();

        $this->assertSame(1, $expired);
        $this->assertDatabaseHas('marketing_bargain_records', ['id' => $recordId, 'status' => 4]);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('当前状态不可下单');

        $this->service->markOrdered(1, $recordId, 'ORD200');
    }

    public function test_cut_blocked_by_blacklist(): void
    {
        $recordId = $this->initiateFor(1);
        DB::table('system_risk_rules')->insert([
            'name' => '用户黑名单', 'rule_type' => 1, 'condition_expr' => '{"user_ids":[10]}', 'action' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        try {
            $this->service->cut(10, $recordId);
            $this->fail('黑名单用户应被拦截');
        } catch (BusinessException $e) {
            $this->assertStringContainsString('风控拦截', $e->getMessage());
        }

        $this->assertDatabaseHas('system_risk_records', ['user_id' => 10, 'object_type' => 5]);
        $this->assertDatabaseCount('marketing_bargain_helpers', 0);
    }

    /**
     * 直接待下单状态（模拟已被砍到底）
     */
    private function forceFloor(int $recordId): void
    {
        DB::table('marketing_bargain_records')->where('id', $recordId)->update([
            'current_price' => (string) self::FLOOR,
            'status' => 2,
            'success_time' => now(),
        ]);
    }
}
