<?php

declare(strict_types=1);

use App\Api\Admin\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;

/*
 * Admin 模块 API：认证复用 web 会话（同源 fetch 自动携带 cookie），
 * 未登录访问返回 401 JSON（见 bootstrap/app.php 的 shouldRenderJsonWhen）。
 */
Route::prefix('admin')->middleware('auth')->group(function () {
    // 附件上传（multipart，独立于生成的 CRUD）
    Route::post('attachment/upload', [AttachmentController::class, 'upload']);

    require __DIR__ . '/route.gen.php';
});
