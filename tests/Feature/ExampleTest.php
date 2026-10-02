<?php

// 根路径 '/' 将由 Portal 模块（app/Modules/Portal）接管，当前以健康检查端点作为应用冒烟测试
test('the application returns a successful response', function () {
    $response = $this->get('/up');

    $response->assertStatus(200);
});
