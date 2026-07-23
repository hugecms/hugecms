<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\DailyStatistic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function recordPageView(Request $request): void
    {
        $date = now()->toDateString();
        $sessionKey = 'stats_recorded_'.$date;
        $isUnique = ! $request->session()->has($sessionKey);

        $statistic = DailyStatistic::firstOrCreate(
            ['date' => $date],
            ['page_views' => 0, 'unique_visitors' => 0, 'new_users' => 0]
        );

        $statistic->increment('page_views');

        if ($isUnique) {
            $statistic->increment('unique_visitors');
            $request->session()->put($sessionKey, true);
        }
    }

    public function recordArticleView(Article $article, Request $request): void
    {
        ArticleView::create([
            'article_id' => $article->id,
            'visitor_hash' => $this->visitorHash($request),
            'ip_address' => $request->ip(),
            'viewed_at' => now(),
        ]);
    }

    public function recordNewUser(): void
    {
        $date = now()->toDateString();

        $statistic = DailyStatistic::firstOrCreate(
            ['date' => $date],
            ['page_views' => 0, 'unique_visitors' => 0, 'new_users' => 0]
        );

        $statistic->increment('new_users');
    }

    /**
     * @return Collection<Article>
     */
    public function popularArticles(int $limit = 10, ?Carbon $start = null, ?Carbon $end = null): Collection
    {
        return Article::query()
            ->where('status', 'published')
            ->withCount(['views' => function ($query) use ($start, $end) {
                if ($start) {
                    $query->where('viewed_at', '>=', $start);
                }
                if ($end) {
                    $query->where('viewed_at', '<=', $end);
                }
            }])
            ->orderByDesc('views_count')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<DailyStatistic>
     */
    public function dailyStats(Carbon $start, Carbon $end): Collection
    {
        return DailyStatistic::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();
    }

    /**
     * @return array{page_views: int, unique_visitors: int, new_users: int}
     */
    public function totals(Carbon $start, Carbon $end): array
    {
        $result = DailyStatistic::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->select([
                DB::raw('SUM(page_views) as page_views'),
                DB::raw('SUM(unique_visitors) as unique_visitors'),
                DB::raw('SUM(new_users) as new_users'),
            ])
            ->first();

        return [
            'page_views' => (int) ($result->page_views ?? 0),
            'unique_visitors' => (int) ($result->unique_visitors ?? 0),
            'new_users' => (int) ($result->new_users ?? 0),
        ];
    }

    protected function visitorHash(Request $request): string
    {
        return hash('sha256', ($request->ip() ?: '').'|'.($request->userAgent() ?: ''));
    }
}
