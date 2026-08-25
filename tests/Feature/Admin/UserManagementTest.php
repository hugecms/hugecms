<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create();
}

test('用户列表页可访问并渲染表格', function () {
    User::factory()->count(3)->create();

    $response = $this->actingAs(adminUser())->get('/admin/user');

    $response->assertOk()->assertSee('用户管理');
    expect($response->getContent())->toContain('<table');
});

test('用户名模糊筛选', function () {
    User::factory()->create(['name' => '张三丰']);
    User::factory()->create(['name' => '李四']);

    $response = $this->actingAs(adminUser())->get('/admin/user?'.http_build_query(['name' => '张三']));

    $response->assertOk()->assertSee('张三丰')->assertDontSee('李四');
});

test('邮箱精确筛选', function () {
    User::factory()->create(['email' => 'target@example.com']);
    User::factory()->create(['email' => 'other@example.com']);

    $response = $this->actingAs(adminUser())->get('/admin/user?email=target@example.com');

    $response->assertOk()->assertSee('target@example.com')->assertDontSee('other@example.com');
});

test('状态筛选', function () {
    User::factory()->create(['name' => '启用人']);
    User::factory()->disabled()->create(['name' => '禁用人']);

    $response = $this->actingAs(adminUser())->get('/admin/user?status=0');

    $response->assertOk()->assertSee('禁用人')->assertDontSee('启用人');
});
