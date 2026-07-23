<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = Cache::remember('sitemap.xml', now()->addHour(), fn () => $this->buildUrls());

        return response()->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }

    /**
     * @return array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>
     */
    protected function buildUrls(): array
    {
        $urls = [];

        $urls[] = [
            'loc' => route('home'),
            'lastmod' => now()->toIso8601String(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        $pageUrls = Page::where('status', ContentStatus::Published->value)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at'])
            ->map(fn (Page $page) => [
                'loc' => route('page.show', $page->slug),
                'lastmod' => $page->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ])
            ->all();

        $articleUrls = Article::where('status', ContentStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at'])
            ->map(fn (Article $article) => [
                'loc' => route('article.show', $article->slug),
                'lastmod' => $article->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ])
            ->all();

        $categoryUrls = Category::orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at'])
            ->map(fn (Category $category) => [
                'loc' => route('category.show', $category->slug),
                'lastmod' => $category->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ])
            ->all();

        return array_merge($urls, $pageUrls, $articleUrls, $categoryUrls);
    }
}
