<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Domains\Marketing\Repositories\MarketingAssistActivityRepository;
use App\Domains\Marketing\Repositories\MarketingAssistHelperRepository;
use App\Domains\Marketing\Repositories\MarketingAssistRecordRepository;
use App\Domains\System\Repositories\SystemRiskRecordRepository;
use App\Domains\System\Repositories\SystemRiskRuleRepository;
use App\Domains\User\Repositories\UserPointLogRepository;
use App\Services\AssistService;
use App\Services\RiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class AssistTest extends TestCase
{
    use RefreshDatabase;

    private AssistService $service;

    private int $activityId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AssistService(
            new MarketingAssistActivityRepository,
            new MarketingAssistRecordRepository,
            new MarketingAssistHelperRepository,
            new UserPointLogRepository,
            new RiskService(new SystemRiskRuleRepository, new SystemRiskRecordRepository),
        );

        $this->activityId = $this->createActivity(2);
    }

    /**
     * 造一个助力活动（默认邀请 2 人、发券）
     */
    private function createActivity(int $requiredCount, int $rewardType = 1, array $config = []): int
    {
        return (int) DB::table('marketing_assist_activities')->insertGetId([
            'name' => '助力测试',
            'reward_type' => $rewardType,
            'reward_ref' => $rewardType === 1 ? 5 : 0,
            'required_count' => $requiredCount,
            'per_limit' => 1,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDay(),
            'config' => $config === [] ? null : json_encode($config, JSON_UNESCAPED_UNICODE),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createUser(int $id): void
    {
        DB::table('users')->insert([
            'id' => $id,
            'username' => 'user'.$id,
            'password' => 'x',
            'points' => 0,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_initiate_creates_record(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);

        $this->assertDatabaseHas('marketing_assist_records', [
            'id' => $recordId, 'user_id' => 1, 'status' => 1, 'helper_count' => 0,
        ]);
    }

    public function test_initiate_rejects_duplicate(): void
    {
        $this->service->initiate(1, $this->activityId);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已发起过该活动');

        $this->service->initiate(1, $this->activityId);
    }

    public function test_assist_grants_coupon_on_target(): void
    {
        DB::table('marketing_coupons')->insert([
            'name' => '助力奖励券', 'type' => 'full_reduce', 'min_amount' => 0, 'reduce_amount' => 10,
            'end_time' => now()->addDays(7), 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $recordId = $this->service->initiate(1, $this->activityId);

        $this->service->assist(10, $recordId);
        $this->service->assist(11, $recordId);

        $this->assertDatabaseHas('marketing_assist_records', [
            'id' => $recordId, 'status' => 2, 'helper_count' => 2,
        ]);
        $this->assertDatabaseHas('marketing_user_coupons', [
            'coupon_id' => 5, 'user_id' => 1, 'status' => 0,
        ]);
    }

    public function test_assist_grants_points_on_target(): void
    {
        $this->createUser(1);
        $pointsActivity = $this->createActivity(1, 2, ['points' => 100]);
        $recordId = $this->service->initiate(1, $pointsActivity);

        $this->service->assist(10, $recordId);

        $this->assertDatabaseHas('users', ['id' => 1, 'points' => 100]);
        $this->assertDatabaseHas('user_point_logs', [
            'user_id' => 1, 'biz_type' => 8, 'change_points' => 100, 'points_after' => 100,
        ]);
    }

    public function test_assist_rejects_duplicate_helper(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);
        $this->service->assist(10, $recordId);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已为该好友助力过');

        $this->service->assist(10, $recordId);
    }

    public function test_assist_rejects_self_help(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('不能为自己的助力');

        $this->service->assist(1, $recordId);
    }

    public function test_assist_rejects_when_completed(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);
        $this->service->assist(10, $recordId);
        $this->service->assist(11, $recordId);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已结束');

        $this->service->assist(12, $recordId);
    }

    public function test_assist_blocked_by_blacklist(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);
        DB::table('system_risk_rules')->insert([
            'name' => '用户黑名单', 'rule_type' => 1, 'condition_expr' => '{"user_ids":[10]}', 'action' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        try {
            $this->service->assist(10, $recordId);
            $this->fail('黑名单用户应被拦截');
        } catch (BusinessException $e) {
            $this->assertStringContainsString('风控拦截', $e->getMessage());
        }

        $this->assertDatabaseHas('system_risk_records', ['user_id' => 10, 'object_type' => 5]);
        $this->assertDatabaseCount('marketing_assist_helpers', 0);
    }

    public function test_expire_due_marks_expired(): void
    {
        $recordId = $this->service->initiate(1, $this->activityId);
        DB::table('marketing_assist_activities')->where('id', $this->activityId)
            ->update(['end_time' => now()->subMinute()]);

        $expired = $this->service->expireDue();

        $this->assertSame(1, $expired);
        $this->assertDatabaseHas('marketing_assist_records', ['id' => $recordId, 'status' => 3]);
    }
}
