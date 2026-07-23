<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\ContentStatus;
use App\Enums\OperationLogType;
use App\Models\Article;
use App\Models\OperationLog;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.recent-activity-widget';

    protected int|string|array $columnSpan = 'full';

    public function getRowsHtml(): string
    {
        $activities = $this->getActivities();

        if ($activities->isEmpty()) {
            return '<tr><td colspan="3" class="px-3 py-4 text-center text-gray-500">暂无动态</td></tr>';
        }

        $html = '';
        foreach ($activities as $activity) {
            $html .= sprintf(
                '<tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-3 py-2"><span class="fi-badge fi-color-%s inline-flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 px-2 min-w-[theme(spacing.6)] py-1">%s</span></td>
                    <td class="px-3 py-2">%s</td>
                    <td class="px-3 py-2">%s</td>
                </tr>',
                $activity['color'],
                e($activity['source']),
                e($activity['content']),
                $activity['created_at']->diffForHumans()
            );
        }

        return $html;
    }

    protected function getActivities(): Collection
    {
        $logins = OperationLog::with('user')
            ->where('type', OperationLogType::Login)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (OperationLog $log): array => [
                'source' => '登录',
                'color' => 'gray',
                'content' => sprintf('用户 %s 登录后台', $log->user->name),
                'created_at' => $log->created_at,
            ]);

        $articles = Article::with('user')
            ->where('status', ContentStatus::Published)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit(5)
            ->get()
            ->map(fn (Article $article): array => [
                'source' => '发布',
                'color' => 'success',
                'content' => sprintf('文章《%s》已发布', $article->title),
                'created_at' => $article->published_at,
            ]);

        $logs = OperationLog::with('user')
            ->whereNotIn('type', [OperationLogType::Login, OperationLogType::Logout])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (OperationLog $log): array => [
                'source' => '操作日志',
                'color' => 'info',
                'content' => sprintf('%s: %s', $log->type->getLabel(), $log->summary ?? '-'),
                'created_at' => $log->created_at,
            ]);

        return collect($logins)
            ->merge($articles)
            ->merge($logs)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }
}
