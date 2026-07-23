<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Tag;
use App\Support\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_page_renders_at_fallback_slug(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'title' => '关于我们',
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('关于我们');
    }

    public function test_draft_page_returns_404(): void
    {
        $page = Page::factory()->draft()->create();

        $this->get(route('page.show', $page->slug))
            ->assertNotFound();
    }

    public function test_category_hierarchy_shown_in_nav(): void
    {
        $menu = Menu::factory()->main()->create();
        $parent = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $parent->id]);

        $parentItem = MenuItem::factory()
            ->forMenu($menu)
            ->category($parent)
            ->create(['title' => $parent->name]);

        MenuItem::factory()
            ->forMenu($menu)
            ->category($child)
            ->create([
                'title' => $child->name,
                'parent_id' => $parentItem->id,
            ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee($parent->name);
        $response->assertSee($child->name);
    }

    public function test_category_archive_includes_subcategory_articles(): void
    {
        $parent = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $parent->id]);
        $article = Article::factory()->create(['status' => ContentStatus::Published]);
        $article->categories()->attach($child);

        $response = $this->get(route('category.show', $parent->slug));

        $response->assertOk();
        $response->assertSee($article->title);
    }

    public function test_cover_image_urls_rendered(): void
    {
        Storage::fake('public');

        $article = Article::factory()->create(['status' => ContentStatus::Published]);
        $article->addMedia(UploadedFile::fake()->image('cover.jpg'))
            ->toMediaCollection('cover');

        $mediumUrl = $article->getFirstMediaUrl('cover', 'medium');
        $largeUrl = $article->getFirstMediaUrl('cover', 'large');

        $this->get(route('home'))
            ->assertSee($mediumUrl, false);

        $this->get(route('article.show', $article->slug))
            ->assertSee($largeUrl, false);
    }

    public function test_drafts_not_shown_in_lists(): void
    {
        $draft = Article::factory()->draft()->create();
        $published = Article::factory()->create(['status' => ContentStatus::Published]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee($published->title);
        $response->assertDontSee($draft->title);
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
