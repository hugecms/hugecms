<?php

declare(strict_types=1);

use think\facade\Route;

Route::group('admin', function () {
    $routeFiles = glob(app_path().'domain/*/route/route.php');
    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
});
