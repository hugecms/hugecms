<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('shop')->group(function () {
    require_once __DIR__.'/route.gen.php';
});
