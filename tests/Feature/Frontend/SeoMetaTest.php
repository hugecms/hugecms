<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Support\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_uses_site_tdk(): void
    {
        SiteSetting::set('site_title', 'HugeCMS 官方站');
        SiteSetting::set('site_description', '企业级内容管理系统');
        SiteSetting::set('site_keywords', 'CMS,Laravel');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('<title>HugeCMS 官方站</title>', false);
        $response->assertSee('<meta name="description" content="企业级内容管理系统">', false);
        $response->assertSee('<meta name="keywords" content="CMS,Laravel">', false);
    }

    public function test_page_uses_seo_fields(): void
    {
        SiteSetting::set('site_title', '默认站点标题');

        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'title' => '关于我们',
            'seo_title' => '关于我们 - 官方',
            'seo_description' => '关于我们描述',
            'seo_keywords' => '关于,我们',
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('<title>关于我们 - 官方</title>', false);
        $response->assertSee('<meta name="description" content="关于我们描述">', false);
        $response->assertSee('<meta name="keywords" content="关于,我们">', false);
    }

    public function test_page_falls_back_to_title_and_site_tdk(): void
    {
        SiteSetting::set('site_description', '默认描述');
        SiteSetting::set('site_keywords', '默认,关键词');

        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'title' => '联系我们',
            'seo_title' => null,
            'seo_description' => null,
            'seo_keywords' => null,
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertSee('<title>联系我们</title>', false);
        $response->assertSee('<meta name="description" content="默认描述">', false);
        $response->assertSee('<meta name="keywords" content="默认,关键词">', false);
    }

    public function test_article_uses_seo_fields(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
            'seo_title' => '文章 SEO 标题',
            'seo_description' => '文章 SEO 描述',
            'seo_keywords' => '文章,SEO',
        ]);

        $response = $this->get(route('article.show', $article->slug));

        $response->assertOk();
        $response->assertSee('<title>文章 SEO 标题</title>', false);
        $response->assertSee('<meta name="description" content="文章 SEO 描述">', false);
        $response->assertSee('<meta name="keywords" content="文章,SEO">', false);
    }

    public function test_category_uses_seo_fields(): void
    {
        $category = Category::factory()->create([
            'name' => '新闻动态',
            'seo_title' => '新闻 SEO 标题',
            'seo_description' => '新闻 SEO 描述',
            'seo_keywords' => '新闻,动态',
        ]);

        $response = $this->get(route('category.show', $category->slug));

        $response->assertOk();
        $response->assertSee('<title>新闻 SEO 标题</title>', false);
        $response->assertSee('<meta name="description" content="新闻 SEO 描述">', false);
        $response->assertSee('<meta name="keywords" content="新闻,动态">', false);
    }
}
