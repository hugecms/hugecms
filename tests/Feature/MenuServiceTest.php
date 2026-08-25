<?php

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('侧边栏菜单按 sort 升序并正确组树', function () {
    Menu::query()->delete();

    $content = Menu::create(['name' => '内容管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '文章管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '栏目管理', 'sort' => 20]);
    Menu::create(['name' => '仪表盘', 'route' => 'admin.dashboard', 'sort' => 0]);

    $result = app(MenuService::class)->getSidebarMenus();

    expect($result)->toHaveCount(2)
        ->and($result[0]['name'])->toBe('仪表盘')
        ->and($result[0]['route'])->toBe('admin.dashboard')
        ->and($result[0]['children'])->toBe([])
        ->and($result[1]['name'])->toBe('内容管理')
        ->and($result[1]['children'])->toHaveCount(2)
        ->and($result[1]['children'][0]['name'])->toBe('文章管理')
        ->and($result[1]['children'][1]['name'])->toBe('栏目管理');
});

test('不可见菜单被过滤', function () {
    Menu::query()->delete();

    Menu::create(['name' => '仪表盘', 'route' => 'admin.dashboard', 'sort' => 0]);
    Menu::create(['name' => '隐藏组', 'visible' => false]);
    $group = Menu::create(['name' => '内容管理', 'sort' => 10]);
    Menu::create(['parent_id' => $group->id, 'name' => '隐藏子项', 'visible' => false]);

    $result = app(MenuService::class)->getSidebarMenus();

    expect($result)->toHaveCount(2)
        ->and($result[1]['children'])->toBeEmpty();
});
