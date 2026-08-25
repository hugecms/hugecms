<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('游客访问仪表盘会跳转登录页', function () {
    $this->get('/admin/dashboard')->assertRedirect('/login');
});

test('登录用户可以访问仪表盘', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk()->assertSee('仪表盘');
});
