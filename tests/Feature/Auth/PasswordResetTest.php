<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

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
