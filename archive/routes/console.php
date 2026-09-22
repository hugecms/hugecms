<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| CMS 定时任务（要求见 docs/development-conventions.md 第六节）
|--------------------------------------------------------------------------
| 服务器部署仅需一条每分钟的 cron：* * * * * php artisan schedule:run
*/

// 回收站过期物理清除（保留期默认 30 天，见 recycle_bin.retention_days / expire_at）
Schedule::command('recycle:purge-expired')->dailyAt('03:10');

// 每日运营数据聚合（昨日；幂等，可手动补算：statistics:aggregate --date=2026-09-01）
Schedule::command('statistics:aggregate')->dailyAt('03:30');
