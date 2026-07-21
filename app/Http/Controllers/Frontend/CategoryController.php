<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Get the category and all its descendants' IDs
        $categoryIds = $category->descendants()->pluck('id')->push($category->id);

        $articles = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'articles'));
    }
}
