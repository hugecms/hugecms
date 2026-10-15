<?php

declare(strict_types=1);

use think\facade\Route;
use think\middleware\Throttle;

Route::group('api', function () {
    $routeFiles = glob(app_path().'api/*/route/route.php');
    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
})->middleware(Throttle::class);
