<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * 初始化超级管理员（幂等：仅当 admin_users 为空时创建）
     * 账号/密码通过环境变量注入：ADMIN_INIT_USERNAME（默认 admin）、ADMIN_INIT_PASSWORD（未配置则生成随机密码并输出）
     * 生产环境请通过环境变量提供强密码，首次登录后立即修改
     */
    public function run(): void
    {
        if (DB::table('admin_users')->exists()) {
            $this->command?->info('admin_users 已存在账号，跳过初始管理员创建');

            return;
        }

        $username = (string) (env('ADMIN_INIT_USERNAME') ?: 'admin');
        $configuredPassword = env('ADMIN_INIT_PASSWORD');
        $password = $configuredPassword ? (string) $configuredPassword : Str::password(16);

        DB::table('admin_users')->insert([
            'username' => $username,
            'password' => Hash::make($password),
            'status' => 1,
            'is_super' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($configuredPassword) {
            $this->command?->info("初始超级管理员已创建：{$username}（密码来自 ADMIN_INIT_PASSWORD）");
        } else {
            $this->command?->warn("初始超级管理员已创建：{$username}，随机密码：{$password}（请立即保存，登录后修改）");
        }
    }
}
