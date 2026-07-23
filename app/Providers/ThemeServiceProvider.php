<?php

namespace App\Providers;

use App\Themes\ThemeRegistry;
use App\Themes\ThemeResolver;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeRegistry::class, function () {
            return new ThemeRegistry(
                ThemeRegistry::defaultBasePath(),
                new Filesystem,
            );
        });

        $this->app->singleton(ThemeResolver::class, function ($app) {
            return new ThemeResolver($app->make(ThemeRegistry::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
