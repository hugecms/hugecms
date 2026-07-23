<?php

use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', HomeController::class)->name('home');

// Sitemap
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Articles
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('article.show');

// Categories
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Tags
Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tag.show');

// Pages
Route::get('page/{slug}', [PageController::class, 'show'])->name('page.show');

// Pages fallback (legacy short URL)
Route::get('/{slug}', [PageController::class, 'show']);
