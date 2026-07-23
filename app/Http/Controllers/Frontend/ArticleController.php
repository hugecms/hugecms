<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Request $request, string $slug, StatisticsService $statistics)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $statistics->recordArticleView($article, $request);

        return view('articles.show', compact('article'));
    }
}
