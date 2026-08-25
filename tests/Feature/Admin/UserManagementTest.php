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

test('新建用户成功', function () {
    $response = $this->actingAs(adminUser())->post('/admin/user', [
        'name' => '新用户',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'status' => '1',
    ]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '创建成功');
    $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'name' => '新用户']);
});

test('新建重复邮箱失败', function () {
    $exist = User::factory()->create();

    $response = $this->actingAs(adminUser())->from('/admin/user/create')->post('/admin/user', [
        'name' => '重复者',
        'email' => $exist->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'status' => '1',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseMissing('users', ['name' => '重复者']);
});

test('编辑页可访问并预填', function () {
    $user = User::factory()->create(['name' => '待编辑']);

    $response = $this->actingAs(adminUser())->get('/admin/user/edit/'.$user->id);

    $response->assertOk()->assertSee('待编辑')->assertSee($user->email);
});

test('更新用户成功且密码留空不改动', function () {
    $user = User::factory()->create();

    $response = $this->actingAs(adminUser())->put('/admin/user/edit/'.$user->id, [
        'name' => '改名了',
        'email' => $user->email,
        'password' => '',
        'password_confirmation' => '',
        'status' => '1',
    ]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '更新成功');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '改名了']);
    $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    $this->assertAuthenticated();
});

test('单删用户成功', function () {
    $user = User::factory()->create();

    $response = $this->actingAs(adminUser())->delete('/admin/user', ['id' => $user->id]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '删除成功');
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('批量删除用户成功', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();
    $keeper = User::factory()->create();

    $response = $this->actingAs(adminUser())->delete('/admin/user', ['ids' => [$a->id, $b->id]]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '删除成功');
    $this->assertDatabaseMissing('users', ['id' => $a->id]);
    $this->assertDatabaseMissing('users', ['id' => $b->id]);
    $this->assertDatabaseHas('users', ['id' => $keeper->id]);
});
