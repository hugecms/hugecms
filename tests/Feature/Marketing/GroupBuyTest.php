<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Domains\Marketing\Repositories\MarketingGroupBuyActivityRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyItemRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamMemberRepository;
use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use App\Services\GroupBuyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;
use Tests\TestCase;

class GroupBuyTest extends TestCase
{
    use RefreshDatabase;

    private GroupBuyService $service;

    private int $activityId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GroupBuyService(
            new MarketingGroupBuyActivityRepository,
            new MarketingGroupBuyItemRepository,
            new MarketingGroupBuyTeamRepository,
            new MarketingGroupBuyTeamMemberRepository,
            new SystemSettingRepository,
        );

        $this->activityId = (int) DB::table('marketing_group_buy_activities')->insertGetId([
            'name' => '拼团测试',
            'spu_id' => 1,
            'group_size' => 2,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDay(),
            'per_limit' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('marketing_group_buy_items')->insert([
            'activity_id' => $this->activityId,
            'sku_id' => 11,
            'group_price' => '59.90',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_open_creates_team_with_leader_member(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');

        $this->assertDatabaseHas('marketing_group_buy_teams', [
            'id' => $teamId, 'leader_user_id' => 1, 'status' => 1, 'member_count' => 1,
        ]);
        $this->assertDatabaseHas('marketing_group_buy_team_members', [
            'team_id' => $teamId, 'user_id' => 1, 'order_no' => 'ORD001',
        ]);
    }

    public function test_join_increments_and_completes_team(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');

        $this->service->joinTeam(2, $teamId, 'ORD002');

        $this->assertDatabaseHas('marketing_group_buy_teams', [
            'id' => $teamId, 'status' => 2, 'member_count' => 2,
        ]);
        $this->assertDatabaseHas('marketing_group_buy_team_members', [
            'team_id' => $teamId, 'user_id' => 2, 'order_no' => 'ORD002',
        ]);
    }

    public function test_join_rejects_when_team_full(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');
        $this->service->joinTeam(2, $teamId, 'ORD002');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已结束');

        $this->service->joinTeam(3, $teamId, 'ORD003');
    }

    public function test_join_rejects_duplicate_user_in_same_team(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已参与该团');

        $this->service->joinTeam(1, $teamId, 'ORD002');
    }

    public function test_open_rejects_reused_order_no(): void
    {
        $this->service->openTeam(1, $this->activityId, 11, 'ORD001');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('该订单已参与过拼团');

        $this->service->openTeam(2, $this->activityId, 11, 'ORD001');
    }

    public function test_per_limit_blocks_repeated_join(): void
    {
        $teamA = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('参团上限');

        $this->service->joinTeam(2, $teamA, 'ORD002');
        // 用户 2 已参一团（per_limit=1），第二团拒绝
        $teamB = $this->service->openTeam(3, $this->activityId, 11, 'ORD003');
        $this->service->joinTeam(2, $teamB, 'ORD004');
    }

    public function test_expire_due_teams_dissolves(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');
        DB::table('marketing_group_buy_teams')->where('id', $teamId)
            ->update(['expire_time' => now()->subMinute()]);

        $expired = $this->service->expireDueTeams();

        $this->assertSame(1, $expired);
        $this->assertDatabaseHas('marketing_group_buy_teams', ['id' => $teamId, 'status' => 3]);
    }

    public function test_join_rejects_after_expire(): void
    {
        $teamId = $this->service->openTeam(1, $this->activityId, 11, 'ORD001');
        DB::table('marketing_group_buy_teams')->where('id', $teamId)
            ->update(['expire_time' => now()->subMinute()]);

        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('已超时');

        $this->service->joinTeam(2, $teamId, 'ORD002');
    }
}
