<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 数据统计看板（statistics_daily，Scheduler 每日聚合）。
 */
class StatisticsController extends Controller
{
    public function index(): View
    {
        return view('admin.statistics.index');
    }
}
