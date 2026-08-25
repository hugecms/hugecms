<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('游客访问用户主页会跳转登录页', function () {
    $this->get('/user/home')->assertRedirect('/login');
});

test('登录用户可以访问用户主页且看到用户名', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/user/home');

    $response->assertOk()->assertSee('我的主页')->assertSee($user->name);
});
