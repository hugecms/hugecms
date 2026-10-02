<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        $this->loadViewsFromModules();
    }

    protected function loadViewsFromModules(): void
    {
        $modules = glob(app_path('Modules/*'), GLOB_ONLYDIR);
        if (! empty($modules)) {
            foreach ($modules as $module) {
                $moduleName = basename($module);
                $this->loadViewsFrom($module.'/Views', Str::snake($moduleName));
            }
        }
    }
}
