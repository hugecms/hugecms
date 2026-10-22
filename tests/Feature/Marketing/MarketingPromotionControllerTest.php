<?php

declare(strict_types=1);

namespace Tests\Feature\Marketing;

use App\Models\AdminUser;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MarketingPromotionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // admin 路由组已挂 admin.auth 中间件，测试以超管身份认证
        $admin = AdminUser::create([
            'username' => 'test-admin',
            'password' => 'secret-123',
            'status' => 1,
            'is_super' => 1,
        ]);
        Sanctum::actingAs($admin, ['*'], 'admin_user');
    }

    /**
     * 构造一份合法的满减活动载荷（指定分类范围 + 两档满减）
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => '双11满减',
            'type' => 'full_reduce',
            'rangeType' => 'category',
            'scopes' => [
                ['scopeType' => 'category', 'targetId' => 1],
                ['scopeType' => 'category', 'targetId' => 2],
            ],
            'ladders' => [
                ['minAmount' => '100', 'reduceAmount' => '10'],
                ['minAmount' => '200', 'reduceAmount' => '30'],
            ],
            'startTime' => '2026-10-01 00:00:00',
            'endTime' => '2026-11-11 23:59:59',
            'status' => 1,
        ], $overrides);
    }

    public function test_store_creates_promotion_with_scopes_and_ladders(): void
    {
        $this->postJson('/api/admin/marketingPromotionFull/create', $this->validPayload())
            ->assertOk()
            ->assertJsonPath('code', 0);

        $this->assertDatabaseCount('marketing_promotions', 1);
        $this->assertDatabaseCount('marketing_promotion_scopes', 2);
        $this->assertDatabaseHas('marketing_promotion_scopes', ['promotion_id' => 1, 'scope_type' => 'category', 'target_id' => 1]);
        $this->assertDatabaseHas('marketing_promotion_scopes', ['promotion_id' => 1, 'scope_type' => 'category', 'target_id' => 2]);
        $this->assertDatabaseCount('marketing_promotion_ladders', 2);
        $this->assertDatabaseHas('marketing_promotion_ladders', ['promotion_id' => 1, 'min_amount' => '100', 'reduce_amount' => '10']);
        $this->assertDatabaseHas('marketing_promotion_ladders', ['promotion_id' => 1, 'min_amount' => '200', 'reduce_amount' => '30']);
    }

    public function test_store_creates_all_range_promotion_without_scope_rows(): void
    {
        $payload = $this->validPayload([
            'rangeType' => 'all',
            'scopes' => null,
            'ladders' => [
                ['minAmount' => '0', 'reduceAmount' => '5'],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertOk()
            ->assertJsonPath('code', 0);

        $this->assertDatabaseCount('marketing_promotions', 1);
        $this->assertDatabaseCount('marketing_promotion_scopes', 0);
        $this->assertDatabaseHas('marketing_promotion_ladders', ['promotion_id' => 1, 'min_amount' => '0']);
    }

    public function test_store_returns_422_when_full_reduce_ladder_misses_reduce_amount(): void
    {
        $payload = $this->validPayload([
            'ladders' => [
                ['minAmount' => '100'],
                ['minAmount' => '200', 'reduceAmount' => '30'],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['ladders.0.reduceAmount' => '满减活动每档必须设置满减金额']);

        $this->assertDatabaseCount('marketing_promotions', 0);
        $this->assertDatabaseCount('marketing_promotion_scopes', 0);
        $this->assertDatabaseCount('marketing_promotion_ladders', 0);
    }

    public function test_store_returns_422_when_discount_ladder_misses_discount_rate(): void
    {
        $payload = $this->validPayload([
            'type' => 'discount',
            'ladders' => [
                ['minAmount' => '100', 'discountRate' => '9.5'],
                ['minAmount' => '200'],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['ladders.1.discountRate' => '折扣活动每档必须设置折扣率']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_discount_ladder_has_reduce_amount(): void
    {
        $payload = $this->validPayload([
            'type' => 'discount',
            'ladders' => [
                ['minAmount' => '100', 'discountRate' => '9.5', 'reduceAmount' => '10'],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['ladders.0.reduceAmount' => '折扣活动档位不能设置满减金额']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_scope_type_mismatches_range_type(): void
    {
        $payload = $this->validPayload([
            'rangeType' => 'category',
            'scopes' => [
                ['scopeType' => 'product', 'targetId' => 1],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scopes.0.scopeType' => '范围类型必须与适用范围一致']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_all_range_has_scopes(): void
    {
        $payload = $this->validPayload([
            'rangeType' => 'all',
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scopes' => '全场活动不能设置适用范围']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_specified_range_misses_scopes(): void
    {
        $payload = $this->validPayload([
            'rangeType' => 'product',
            'scopes' => null,
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scopes' => '请设置适用范围']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_ladder_min_amounts_duplicate(): void
    {
        $payload = $this->validPayload([
            'ladders' => [
                ['minAmount' => '100', 'reduceAmount' => '10'],
                ['minAmount' => '100', 'reduceAmount' => '20'],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['ladders.1.minAmount' => '档位门槛金额不能重复']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_store_returns_422_when_scope_targets_duplicate(): void
    {
        $payload = $this->validPayload([
            'scopes' => [
                ['scopeType' => 'category', 'targetId' => 1],
                ['scopeType' => 'category', 'targetId' => 1],
            ],
        ]);

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scopes.1.targetId' => '适用范围目标重复']);

        $this->assertDatabaseCount('marketing_promotions', 0);
    }

    public function test_database_rejects_duplicate_scope_rows(): void
    {
        $row = ['promotion_id' => 1, 'scope_type' => 'category', 'target_id' => 1,
            'created_at' => '2026-10-01 00:00:00', 'updated_at' => '2026-10-01 00:00:00'];

        DB::table('marketing_promotion_scopes')->insert($row);

        $this->expectException(QueryException::class);

        DB::table('marketing_promotion_scopes')->insert($row);
    }

    public function test_database_rejects_duplicate_ladder_thresholds(): void
    {
        $row = ['promotion_id' => 1, 'min_amount' => '100', 'reduce_amount' => '10',
            'created_at' => '2026-10-01 00:00:00', 'updated_at' => '2026-10-01 00:00:00'];

        DB::table('marketing_promotion_ladders')->insert($row);

        $this->expectException(QueryException::class);

        DB::table('marketing_promotion_ladders')->insert(['promotion_id' => 1, 'min_amount' => '100', 'reduce_amount' => '99',
            'created_at' => '2026-10-01 00:00:00', 'updated_at' => '2026-10-01 00:00:00']);
    }

    public function test_show_returns_scopes_and_ladders(): void
    {
        $this->postJson('/api/admin/marketingPromotionFull/create', $this->validPayload())
            ->assertOk();

        $response = $this->getJson('/api/admin/marketingPromotionFull/show?id=1')
            ->assertOk()
            ->assertJsonPath('code', 0)
            ->assertJsonCount(2, 'data.scopes')
            ->assertJsonCount(2, 'data.ladders');

        $targetIds = array_column($response->json('data.scopes'), 'targetId');
        sort($targetIds);
        $this->assertSame([1, 2], $targetIds);
    }

    public function test_search_omits_children_keys(): void
    {
        $this->postJson('/api/admin/marketingPromotionFull/create', $this->validPayload())
            ->assertOk();

        $response = $this->postJson('/api/admin/marketingPromotion/search?page=1&pageSize=10', [])
            ->assertOk()
            ->assertJsonCount(1, 'data.data');

        $row = $response->json('data.data.0');
        $this->assertArrayNotHasKey('scopes', $row);
        $this->assertArrayNotHasKey('ladders', $row);
    }

    public function test_update_replaces_children_rows(): void
    {
        $payload = $this->validPayload();

        $this->postJson('/api/admin/marketingPromotionFull/create', $payload)->assertOk();

        $id = (int) DB::table('marketing_promotions')->where('name', '双11满减')->value('id');

        $payload['ladders'] = [
            ['minAmount' => '300', 'reduceAmount' => '50'],
        ];
        $payload['scopes'] = [
            ['scopeType' => 'category', 'targetId' => 9],
        ];

        $this->postJson("/api/admin/marketingPromotionFull/update?id={$id}", $payload)
            ->assertOk()
            ->assertJsonPath('code', 0);

        $this->assertDatabaseCount('marketing_promotions', 1);
        $this->assertDatabaseCount('marketing_promotion_scopes', 1);
        $this->assertDatabaseHas('marketing_promotion_scopes', ['promotion_id' => $id, 'target_id' => 9]);
        $this->assertDatabaseCount('marketing_promotion_ladders', 1);
        $this->assertDatabaseMissing('marketing_promotion_ladders', ['promotion_id' => $id, 'min_amount' => '100']);
        $this->assertDatabaseHas('marketing_promotion_ladders', ['promotion_id' => $id, 'min_amount' => '300']);
    }

    public function test_destroy_purges_children_rows(): void
    {
        $this->postJson('/api/admin/marketingPromotionFull/create', $this->validPayload())
            ->assertOk();

        $id = (int) DB::table('marketing_promotions')->where('name', '双11满减')->value('id');

        $this->postJson('/api/admin/marketingPromotionFull/destroy', ['ids' => [$id]])
            ->assertOk()
            ->assertJsonPath('code', 0);

        $this->assertDatabaseCount('marketing_promotions', 0);
        $this->assertDatabaseCount('marketing_promotion_scopes', 0);
        $this->assertDatabaseCount('marketing_promotion_ladders', 0);
    }
}
