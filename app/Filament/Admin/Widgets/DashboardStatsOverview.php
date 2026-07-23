<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\CommentStatus;
use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected ?string $heading = '数据概览';

    protected function getStats(): array
    {
        return [
            Stat::make('文章总数', Article::count())
                ->description('已发布内容')
                ->icon(Heroicon::OutlinedDocumentText),
            Stat::make('用户总数', User::count())
                ->description('注册用户')
                ->icon(Heroicon::OutlinedUsers),
            Stat::make('今日访问量', '未接入')
                ->description('统计模块预留')
                ->icon(Heroicon::OutlinedChartBar),
            Stat::make('待审核内容', $this->pendingCount())
                ->description('文章 + 评论')
                ->icon(Heroicon::OutlinedClock)
                ->color('warning'),
        ];
    }

    protected function pendingCount(): int
    {
        $articles = Article::where('status', ContentStatus::Pending)->count();
        $comments = Comment::where('status', CommentStatus::Pending)->count();

        return $articles + $comments;
    }
}
