<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('common')->name('common.')->group(function () {
    if (file_exists(__DIR__ . '/route.gen.php')) {
        require __DIR__ . '/route.gen.php';
    }
});
