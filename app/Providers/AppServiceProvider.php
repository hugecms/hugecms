<?php

namespace App\Providers;

use App\Enums\OperationLogType;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Link;
use App\Models\Media;
use App\Models\MediaCategory;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use App\Observers\OperationLogObserver;
use App\Observers\SitemapCacheObserver;
use App\Policies\RolePolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn (User $user) => $user->hasRole('super_admin') ? true : null);

        // Spatie 的 Role 模型在 vendor 命名空间下，Laravel 无法自动猜测到 App\Policies\RolePolicy
        Gate::policy(Role::class, RolePolicy::class);

        $this->registerOperationLogObserver();
        $this->registerAuthEventListeners();
        $this->registerSitemapCacheObserver();
    }

    protected function registerSitemapCacheObserver(): void
    {
        $sitemapModels = [
            Page::class,
            Article::class,
            Category::class,
            Setting::class,
        ];

        foreach ($sitemapModels as $model) {
            $model::observe(SitemapCacheObserver::class);
        }
    }

    protected function registerOperationLogObserver(): void
    {
        $loggableModels = [
            Article::class,
            Page::class,
            Category::class,
            Tag::class,
            Comment::class,
            Media::class,
            MediaCategory::class,
            Banner::class,
            Announcement::class,
            Link::class,
            User::class,
            Role::class,
        ];

        foreach ($loggableModels as $model) {
            $model::observe(OperationLogObserver::class);
        }
    }

    protected function registerAuthEventListeners(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            OperationLogObserver::record('system', OperationLogType::Login);
        });

        Event::listen(Logout::class, function (Logout $event): void {
            OperationLogObserver::record('system', OperationLogType::Logout);
        });
    }
}
