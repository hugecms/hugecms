<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    require __DIR__.'/route.gen.php';
    $routeFiles = glob(app_path('Domains/*/Routes/route.gen.php'));
    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
});
