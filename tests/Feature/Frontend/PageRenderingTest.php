<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Tag;
use App\Support\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_published_articles(): void
    {
        $articles = Article::factory()->count(13)->create(['status' => ContentStatus::Published]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee($articles->first()->title);
        $response->assertSee('Go to page 2', false);
    }

    public function test_article_detail_renders(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
            'content' => '<p>文章正文</p>',
        ]);

        $response = $this->get(route('article.show', $article->slug));

        $response->assertOk();
        $response->assertSee($article->title);
        $response->assertSee('文章正文');
    }

    public function test_draft_article_returns_404(): void
    {
        $article = Article::factory()->draft()->create();

        $this->get(route('article.show', $article->slug))
            ->assertNotFound();
    }

    public function test_category_archive_renders_articles(): void
    {
        $category = Category::factory()->create();
        $article = Article::factory()->create(['status' => ContentStatus::Published]);
        $article->categories()->attach($category);

        $response = $this->get(route('category.show', $category->slug));

        $response->assertOk();
        $response->assertSee($category->name);
        $response->assertSee($article->title);
    }

    public function test_tag_archive_renders_articles(): void
    {
        $tag = Tag::factory()->create();
        $article = Article::factory()->create(['status' => ContentStatus::Published]);
        $article->tags()->attach($tag);

        $response = $this->get(route('tag.show', $tag->slug));

        $response->assertOk();
        $response->assertSee($tag->name);
        $response->assertSee($article->title);
    }

    public function test_page_renders_at_page_slug(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'title' => '关于我们',
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('关于我们');
        $response->assertSee('/page/'.$page->slug, false);
    }

    public function test_old_slug_fallback_still_works(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'title' => '联系',
        ]);

        $response = $this->get('/'.$page->slug);

        $response->assertOk();
        $response->assertSee('联系');
    }

    public function test_layout_uses_site_settings(): void
    {
        SiteSetting::set('site_name', 'HugeCMS 官网');
        SiteSetting::set('copyright', '版权所有');
        SiteSetting::set('icp', '京ICP备12345678号');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('HugeCMS 官网');
        $response->assertSee('版权所有');
        $response->assertSee('京ICP备12345678号');
    }
}
