<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    if (file_exists(__DIR__.'/route.gen.php')) {
        require __DIR__.'/route.gen.php';
    }

    // 领域 CRUD 接口（gen:controller 生成于 app/Domains/*/Controllers，挂载到 Admin API）
    $domainRoutes = glob(app_path('Domains/*/Routes/route.gen.php'));
    if (!empty($domainRoutes)) {
        foreach ($domainRoutes as $domainRoute) {
            require $domainRoute;
        }
    }
});
