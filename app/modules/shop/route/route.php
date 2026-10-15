<?php

declare(strict_types=1);

use app\modules\shop\controller\IndexController;
use think\facade\Route;

Route::group('shop', function () {
    Route::get('/', [IndexController::class, 'index']);
});
