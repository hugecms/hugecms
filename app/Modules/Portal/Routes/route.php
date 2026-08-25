<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    require __DIR__.'/route.gen.php';
});
