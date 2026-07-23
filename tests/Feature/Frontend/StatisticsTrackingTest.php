<?php

namespace Tests\Feature\Frontend;

use App\Models\Article;
use App\Models\DailyStatistic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class StatisticsTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::findOrCreate('member', 'web');
    }

    public function test_visiting_home_records_page_view(): void
    {
        $this->assertDatabaseMissing('daily_statistics', ['date' => now()->toDateString()]);

        $this->get(route('home'));

        $this->assertDatabaseHas('daily_statistics', [
            'date' => now()->toDateString(),
            'page_views' => 1,
            'unique_visitors' => 1,
        ]);
    }

    public function test_visiting_article_records_article_view(): void
    {
        $article = Article::factory()->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('article.show', $article->slug));

        $this->assertDatabaseHas('article_views', [
            'article_id' => $article->id,
        ]);

        $statistic = DailyStatistic::first();
        $this->assertNotNull($statistic);
        $this->assertSame(1, $statistic->page_views);
    }

    public function test_registration_records_new_user_statistic(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('daily_statistics', [
            'date' => now()->toDateString(),
            'new_users' => 1,
        ]);
    }
}
