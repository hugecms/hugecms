<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * 后台初始菜单树（route 为命名路由；占位项 route 留空）
     */
    public function run(): void
    {
        Menu::create([
            'name' => '仪表盘',
            'route' => 'admin.dashboard',
            'sort' => 0,
        ]);

        $content = Menu::create([
            'name' => '内容管理',
            'sort' => 10,
        ]);
        Menu::create(['parent_id' => $content->id, 'name' => '文章管理', 'sort' => 10]);
        Menu::create(['parent_id' => $content->id, 'name' => '栏目管理', 'sort' => 20]);

        $userPerm = Menu::create([
            'name' => '用户与权限',
            'sort' => 20,
        ]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '用户管理', 'sort' => 10]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '角色管理', 'sort' => 20]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '菜单管理', 'sort' => 30]);

        $system = Menu::create([
            'name' => '系统管理',
            'sort' => 30,
        ]);
        Menu::create(['parent_id' => $system->id, 'name' => '系统设置', 'sort' => 10]);
        Menu::create(['parent_id' => $system->id, 'name' => '操作日志', 'sort' => 20]);
    }
}
