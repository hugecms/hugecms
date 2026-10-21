<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemRiskSeeder extends Seeder
{
    /**
     * 初始化风控规则种子：用户黑名单（空名单）+ 提现高频（默认阈值）
     */
    public function run(): void
    {
        // 幂等：已有规则则跳过
        if (DB::table('system_risk_rules')->exists()) {
            return;
        }

        $now = now();

        DB::table('system_risk_rules')->insert([
            [
                'name' => '用户黑名单',
                'rule_type' => 1,
                'condition_expr' => '{"user_ids":[]}',
                'action' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '提现高频拦截',
                'rule_type' => 2,
                'condition_expr' => '{"max_count":3,"window_minutes":60}',
                'action' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
