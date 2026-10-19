<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use Juling\Foundation\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    /**
     * 获取当前时间戳
     */
    protected function getCurrentTimestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }

    /**
     * 获取当前毫秒时间戳
     */
    protected function getCurrentMillisecond(): int
    {
        return Carbon::now()->getTimestampMs();
    }
}
