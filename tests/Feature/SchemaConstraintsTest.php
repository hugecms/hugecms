<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SchemaConstraintsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function orderRow(array $overrides = []): array
    {
        return array_merge([
            'order_no' => 'ORD'.uniqid(),
            'user_id' => 1,
            'total_amount' => '100.00',
            'freight_amount' => '0.00',
            'discount_amount' => '0.00',
            'pay_amount' => '100.00',
            'receiver_name' => '张三',
            'receiver_phone' => '13800000000',
            'receiver_region' => '广东省深圳市南山区',
            'receiver_address' => '科技园1号',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides);
    }

    public function test_orders_allow_multiple_null_request_tokens(): void
    {
        DB::table('orders')->insert($this->orderRow());
        DB::table('orders')->insert($this->orderRow());

        $this->assertSame(2, DB::table('orders')->whereNull('request_token')->count());
    }

    public function test_orders_reject_duplicate_request_token(): void
    {
        DB::table('orders')->insert($this->orderRow(['request_token' => 'TOKEN-1']));

        $this->expectException(QueryException::class);

        DB::table('orders')->insert($this->orderRow(['request_token' => 'TOKEN-1']));
    }

    public function test_users_allow_multiple_null_share_codes(): void
    {
        DB::table('users')->insert(['username' => 'u1', 'password' => 'x', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('users')->insert(['username' => 'u2', 'password' => 'x', 'created_at' => now(), 'updated_at' => now()]);

        $this->assertSame(2, DB::table('users')->whereNull('share_code')->count());
    }

    public function test_users_reject_duplicate_share_code(): void
    {
        DB::table('users')->insert(['username' => 'u1', 'password' => 'x', 'share_code' => 'ABC123', 'created_at' => now(), 'updated_at' => now()]);

        $this->expectException(QueryException::class);

        DB::table('users')->insert(['username' => 'u2', 'password' => 'x', 'share_code' => 'ABC123', 'created_at' => now(), 'updated_at' => now()]);
    }
}
