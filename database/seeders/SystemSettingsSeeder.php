<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * 初始化站点配置：basic 基础 + seo + distribution 分销 + funds 资金 + risk 风控
     */
    public function run(): void
    {
        // 幂等：已初始化过则跳过
        if (DB::table('system_settings')->where('key', 'site_name')->exists()) {
            return;
        }

        $now = now();

        $settings = [
            ['group' => 'basic', 'key' => 'site_name', 'title' => '站点名称', 'value' => 'ECBoot', 'type' => 'text'],
            ['group' => 'basic', 'key' => 'site_description', 'title' => '站点描述', 'value' => '', 'type' => 'textarea'],
            ['group' => 'basic', 'key' => 'site_logo', 'title' => '站点LOGO', 'value' => '', 'type' => 'image'],
            ['group' => 'basic', 'key' => 'icp_number', 'title' => 'ICP备案号', 'value' => '', 'type' => 'text'],
            ['group' => 'basic', 'key' => 'icp_url', 'title' => '备案链接', 'value' => 'https://beian.miit.gov.cn', 'type' => 'text'],
            ['group' => 'basic', 'key' => 'copyright', 'title' => '版权信息', 'value' => 'Copyright © ECBoot. All Rights Reserved.', 'type' => 'text'],
            ['group' => 'basic', 'key' => 'contact_email', 'title' => '联系邮箱', 'value' => '', 'type' => 'text'],
            ['group' => 'basic', 'key' => 'contact_phone', 'title' => '联系电话', 'value' => '', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'site_keywords', 'title' => 'SEO关键词', 'value' => '', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'site_title', 'title' => 'SEO标题', 'value' => '', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'site_description_seo', 'title' => 'SEO描述', 'value' => '', 'type' => 'textarea'],
            ['group' => 'distribution', 'key' => 'distribution.level1_rate', 'title' => '一级佣金比例(%)', 'value' => '5.00', 'type' => 'number'],
            ['group' => 'distribution', 'key' => 'distribution.level2_rate', 'title' => '二级佣金比例(%)', 'value' => '2.00', 'type' => 'number'],
            ['group' => 'distribution', 'key' => 'distribution.protect_days', 'title' => '佣金保护期(天)', 'value' => '7', 'type' => 'number'],
            ['group' => 'funds', 'key' => 'withdraw.min_amount', 'title' => '提现最低金额(元)', 'value' => '10.00', 'type' => 'number'],
            ['group' => 'funds', 'key' => 'withdraw.daily_limit', 'title' => '每日提现申请次数上限', 'value' => '1', 'type' => 'number'],
            ['group' => 'risk', 'key' => 'risk.withdraw_high_freq.max_count', 'title' => '提现高频阈值(次/窗口)', 'value' => '3', 'type' => 'number'],
            ['group' => 'risk', 'key' => 'risk.withdraw_high_freq.window_minutes', 'title' => '提现高频统计窗口(分钟)', 'value' => '60', 'type' => 'number'],
            ['group' => 'marketing', 'key' => 'group_buy.expire_hours', 'title' => '拼团成团时限(小时)', 'value' => '24', 'type' => 'number'],
            ['group' => 'marketing', 'key' => 'bargain.expire_hours', 'title' => '砍价截止时限(小时)', 'value' => '24', 'type' => 'number'],
        ];

        DB::table('system_settings')->insert(array_map(
            fn (array $setting): array => $setting + ['created_at' => $now, 'updated_at' => $now],
            $settings
        ));
    }
}
