<?php

namespace Tests\Feature;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_sitemap_includes_published_pages_articles_and_categories(): void
    {
        $page = Page::factory()->create(['status' => ContentStatus::Published]);
        $article = Article::factory()->create(['status' => ContentStatus::Published]);
        $category = Category::factory()->create();

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee(route('home'));
        $response->assertSee(route('page.show', $page->slug));
        $response->assertSee(route('article.show', $article->slug));
        $response->assertSee(route('category.show', $category->slug));
    }

    public function test_sitemap_excludes_draft_and_offline_pages(): void
    {
        $draft = Page::factory()->draft()->create();
        $offline = Page::factory()->create(['status' => ContentStatus::Offline]);
        $published = Page::factory()->create(['status' => ContentStatus::Published]);

        $response = $this->get(route('sitemap'));

        $response->assertSee(route('page.show', $published->slug));
        $response->assertDontSee(route('page.show', $draft->slug));
        $response->assertDontSee(route('page.show', $offline->slug));
    }

    public function test_sitemap_excludes_unpublished_articles(): void
    {
        $draft = Article::factory()->draft()->create();
        $future = Article::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => now()->addDay(),
        ]);
        $published = Article::factory()->create(['status' => ContentStatus::Published]);

        $response = $this->get(route('sitemap'));

        $response->assertSee(route('article.show', $published->slug));
        $response->assertDontSee(route('article.show', $draft->slug));
        $response->assertDontSee(route('article.show', $future->slug));
    }

    public function test_sitemap_cache_is_cleared_when_content_changes(): void
    {
        $response = $this->get(route('sitemap'));
        $response->assertOk();

        $page = Page::factory()->create(['status' => ContentStatus::Published]);

        $this->get(route('sitemap'))
            ->assertSee(route('page.show', $page->slug));
    }
}
