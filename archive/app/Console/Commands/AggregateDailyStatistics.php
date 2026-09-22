<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Content;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Signature('statistics:aggregate {--date= : 统计日期 Y-m-d，默认昨天}')]
#[Description('聚合指定日期运营数据到 statistics_daily（幂等，按日覆盖更新，见开发约定第六节）')]
class AggregateDailyStatistics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $date = $this->option('date') ? Carbon::parse((string) $this->option('date')) : Carbon::yesterday();

        $day = $date->toDateString();
        $start = $day . ' 00:00:00';
        $end = $day . ' 23:59:59';

        $metrics = [
            'new_contents' => (int) Content::query()->whereBetween('created_at', [$start, $end])->count(),
            'published_contents' => (int) Content::query()
                ->where('status', 'published')
                ->whereBetween('published_at', [$start, $end])
                ->count(),
            'total_contents' => (int) Content::query()->where('status', '!=', 'trash')->count(),
            'total_views' => (int) Content::query()->sum('views'),
            'new_comments' => (int) Comment::query()->whereBetween('created_at', [$start, $end])->count(),
            'new_users' => (int) User::query()->whereBetween('created_at', [$start, $end])->count(),
            'active_users' => (int) User::query()->whereBetween('last_login_time', [$start, $end])->count(),
            'total_users' => (int) User::query()->count(),
            'updated_at' => now(),
        ];

        // 幂等：同一天重复执行覆盖更新（如补算历史日期）
        $exists = DB::table('statistics_daily')->where('stat_date', $day)->exists();
        if ($exists) {
            DB::table('statistics_daily')->where('stat_date', $day)->update($metrics);
        } else {
            $metrics['stat_date'] = $day;
            $metrics['created_at'] = now();
            DB::table('statistics_daily')->insert($metrics);
        }

        $this->info("已聚合 {$day} 的统计数据。");

        return self::SUCCESS;
    }
}
