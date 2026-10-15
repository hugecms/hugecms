<?php

declare(strict_types=1);

use think\facade\Route;
use think\middleware\SessionInit;

Route::group(function () {
    $routeFiles = glob(app_path().'modules/*/route/route.php');
    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
    Route::group(route_rules());
})->middleware(SessionInit::class);
