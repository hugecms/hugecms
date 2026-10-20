<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * 初始化站点配置：basic 基础 + seo 分组
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
        ];

        DB::table('system_settings')->insert(array_map(
            fn (array $setting): array => $setting + ['created_at' => $now, 'updated_at' => $now],
            $settings
        ));
    }
}
