<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MemberController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\TagController;
use App\Http\Middleware\RecordStatistics;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::middleware(RecordStatistics::class)->group(function () {
    // Home
    Route::get('/', HomeController::class)->name('home');

    // Sitemap
    Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

    // Authentication
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // Member center
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('member', [MemberController::class, 'dashboard'])->name('member.dashboard');
        Route::put('member/profile', [MemberController::class, 'updateProfile'])->name('member.profile.update');
        Route::put('member/password', [MemberController::class, 'updatePassword'])->name('member.password.update');
        Route::post('member/avatar', [MemberController::class, 'updateAvatar'])->name('member.avatar.update');
    });

    // Articles
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('article.show');

    // Categories
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('category.show');

    // Tags
    Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tag.show');

    // Pages (fallback — must be last)
    Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
});
