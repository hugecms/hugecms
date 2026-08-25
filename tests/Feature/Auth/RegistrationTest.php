<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('注册页面可以正常显示', function () {
    $this->get('/register')->assertOk()->assertSee('注册账号');
});

test('用户可以注册并自动登录', function () {
    $response = $this->post('/register', [
        'name' => '测试用户',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'name' => '测试用户']);
    $response->assertRedirect('/');
});

test('重复邮箱不能注册', function () {
    $user = User::factory()->create();

    $response = $this->from('/register')->post('/register', [
        'name' => '测试用户',
        'email' => $user->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['name' => '测试用户']);
    $response->assertSessionHasErrors('email');
});

test('弱密码不能注册', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => '测试用户',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});
