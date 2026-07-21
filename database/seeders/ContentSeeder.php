<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrFail();

        // --- Categories ---
        $news = Category::create(['name' => '新闻', 'slug' => 'news']);
        $company = $news->children()->create(['name' => '公司动态', 'slug' => 'company']);
        $news->children()->create(['name' => '行业资讯', 'slug' => 'industry']);

        $blog = Category::create(['name' => '技术博客', 'slug' => 'blog']);
        $blog->children()->create(['name' => 'PHP', 'slug' => 'php']);

        // --- Tags ---
        $tagPhp = Tag::create(['name' => 'PHP', 'slug' => 'php']);
        $tagLaravel = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tagFilament = Tag::create(['name' => 'Filament', 'slug' => 'filament']);

        // --- Articles ---
        $article = Article::create([
            'title' => '欢迎使用 HugeCMS',
            'slug' => 'hello-hugecms',
            'excerpt' => 'HugeCMS 是基于 Laravel 和 Filament 的企业级内容管理系统。',
            'content' => '<h2>欢迎</h2><p>这是一篇示例文章，展示 HugeCMS 的 RichEditor 内容编辑能力。</p><blockquote>HugeCMS = Laravel 13 + Filament 5 + RBAC</blockquote>',
            'status' => 'published',
            'published_at' => now(),
            'user_id' => $admin->id,
            'seo_title' => '欢迎使用 HugeCMS',
            'seo_description' => 'HugeCMS 企业级内容管理系统',
        ]);
        $article->categories()->sync([$company->id]);
        $article->tags()->sync([$tagPhp->id, $tagLaravel->id, $tagFilament->id]);

        // --- Pages ---
        $about = Page::create([
            'title' => '关于我们',
            'slug' => 'about',
            'content' => '<h2>关于 HugeCMS</h2><p>HugeCMS 专为企业网站打造，提供文章管理、分类管理、标签系统和页面管理。</p>',
            'status' => 'published',
            'user_id' => $admin->id,
        ]);
        $about->children()->create([
            'title' => '联系我们',
            'slug' => 'contact',
            'content' => '<h2>联系我们</h2><p>欢迎通过以下方式联系我们。</p>',
            'status' => 'published',
            'user_id' => $admin->id,
        ]);
    }
}
