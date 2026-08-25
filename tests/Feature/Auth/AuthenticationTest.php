<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('登录页面可以正常显示', function () {
    $this->get('/login')->assertOk()->assertSee('登录 HugeCMS');
});
