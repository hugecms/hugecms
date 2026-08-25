<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password as PasswordBroker;

uses(RefreshDatabase::class);

test('忘记密码页面可以正常显示', function () {
    $this->get('/forgot-password')->assertOk()->assertSee('忘记密码');
});

test('有效邮箱发送密码重置邮件', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
    $response->assertSessionHas('status', '重置链接已发送，请查收邮件');
});

test('未注册邮箱返回错误', function () {
    $response = $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody@example.com']);

    $response->assertSessionHasErrors(['email' => '未找到该邮箱对应的用户']);
});

test('重置密码页面可以正常显示', function () {
    $this->get('/reset-password?token=abc')->assertOk()->assertSee('重置密码');
});

test('用户可以使用有效令牌重置密码', function () {
    $user = User::factory()->create();
    $token = PasswordBroker::createToken($user);

    $response = $this->from('/reset-password')->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHas('status', '密码重置成功，请使用新密码登录');

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'new-password123',
    ]);
    $this->assertAuthenticated();
});

test('无效令牌不能重置密码', function () {
    $user = User::factory()->create();

    $response = $this->from('/reset-password')->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertSessionHasErrors(['email' => '重置链接无效或已过期']);
});
