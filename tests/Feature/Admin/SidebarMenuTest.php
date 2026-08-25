<?php

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function seedSidebarMenus(): void
{
    Menu::create(['name' => '仪表盘', 'route' => 'admin.dashboard', 'sort' => 0]);

    $content = Menu::create(['name' => '内容管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '文章管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '栏目管理', 'sort' => 20]);
}

test('后台侧边栏渲染菜单树', function () {
    seedSidebarMenus();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk()
        ->assertSee('仪表盘')
        ->assertSee('内容管理')
        ->assertSee('文章管理')
        ->assertSee('栏目管理');
});

test('不可见菜单不出现在侧边栏', function () {
    seedSidebarMenus();
    $user = User::factory()->create();
    Menu::where('name', '栏目管理')->update(['visible' => false]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk()->assertDontSee('栏目管理');
});
