<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;

class HomeController extends Controller
{
    public function __invoke()
    {
        $articles = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('home', compact('articles'));
    }
}
