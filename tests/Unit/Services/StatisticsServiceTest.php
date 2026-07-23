<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\DailyStatistic;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class StatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function makeRequest(): Request
    {
        $request = Request::create('/');
        $request->setLaravelSession($this->app['session.store']);

        return $request;
    }

    public function test_record_page_view_creates_daily_statistic(): void
    {
        $service = app(StatisticsService::class);

        $service->recordPageView($this->makeRequest());

        $statistic = DailyStatistic::first();
        $this->assertNotNull($statistic);
        $this->assertSame(1, $statistic->page_views);
        $this->assertSame(1, $statistic->unique_visitors);
    }

    public function test_record_page_view_counts_unique_visitor_once_per_session(): void
    {
        $service = app(StatisticsService::class);
        $request = $this->makeRequest();

        $service->recordPageView($request);
        $service->recordPageView($request);

        $statistic = DailyStatistic::first();
        $this->assertSame(2, $statistic->page_views);
        $this->assertSame(1, $statistic->unique_visitors);
    }

    public function test_record_new_user_increments_daily_statistic(): void
    {
        $service = app(StatisticsService::class);

        $service->recordNewUser();

        $statistic = DailyStatistic::first();
        $this->assertSame(1, $statistic->new_users);
    }

    public function test_record_article_view_creates_article_view(): void
    {
        $service = app(StatisticsService::class);
        $article = Article::factory()->create();

        $service->recordArticleView($article, $this->makeRequest());

        $this->assertDatabaseHas('article_views', [
            'article_id' => $article->id,
        ]);
    }

    public function test_popular_articles_are_ordered_by_views(): void
    {
        $service = app(StatisticsService::class);
        $popular = Article::factory()->create(['status' => 'published']);
        $unpopular = Article::factory()->create(['status' => 'published']);

        $popular->views()->create([
            'visitor_hash' => 'hash-1',
            'viewed_at' => now(),
        ]);
        $popular->views()->create([
            'visitor_hash' => 'hash-2',
            'viewed_at' => now(),
        ]);
        $unpopular->views()->create([
            'visitor_hash' => 'hash-3',
            'viewed_at' => now(),
        ]);

        $articles = $service->popularArticles(limit: 10);

        $this->assertCount(2, $articles);
        $this->assertSame($popular->id, $articles->first()->id);
        $this->assertSame(2, $articles->first()->views_count);
    }

    public function test_daily_stats_are_returned_in_range(): void
    {
        $service = app(StatisticsService::class);
        DailyStatistic::factory()->forDate('2026-07-20')->create();
        DailyStatistic::factory()->forDate('2026-07-21')->create();
        DailyStatistic::factory()->forDate('2026-07-22')->create();

        $stats = $service->dailyStats(
            Carbon::parse('2026-07-20'),
            Carbon::parse('2026-07-21')
        );

        $this->assertCount(2, $stats);
    }

    public function test_totals_aggregate_statistics_in_range(): void
    {
        $service = app(StatisticsService::class);
        DailyStatistic::factory()->forDate('2026-07-20')->create([
            'page_views' => 10,
            'unique_visitors' => 5,
            'new_users' => 2,
        ]);
        DailyStatistic::factory()->forDate('2026-07-21')->create([
            'page_views' => 20,
            'unique_visitors' => 10,
            'new_users' => 3,
        ]);

        $totals = $service->totals(
            Carbon::parse('2026-07-20'),
            Carbon::parse('2026-07-21')
        );

        $this->assertSame(30, $totals['page_views']);
        $this->assertSame(15, $totals['unique_visitors']);
        $this->assertSame(5, $totals['new_users']);
    }
}
