<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * 权限点（含已有 Admin API 的接口权限）
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'admin.dashboard', 'title' => '访问仪表盘', 'module' => 'dashboard'],
            ['name' => 'admin.user.index', 'title' => '查看用户列表', 'module' => 'user'],
            ['name' => 'admin.user.search', 'title' => '搜索用户', 'module' => 'user'],
            ['name' => 'admin.user.store', 'title' => '创建用户', 'module' => 'user'],
            ['name' => 'admin.user.update', 'title' => '更新用户', 'module' => 'user'],
            ['name' => 'admin.user.destroy', 'title' => '删除用户', 'module' => 'user'],
            ['name' => 'admin.menu.index', 'title' => '查看菜单', 'module' => 'menu'],
            ['name' => 'admin.role.index', 'title' => '查看角色', 'module' => 'role'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
