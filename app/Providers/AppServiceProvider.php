<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        $this->loadModules();
    }

    private function loadModules(): void
    {
        $modules = glob(app_path('Modules/*'), GLOB_ONLYDIR);
        foreach ($modules as $module) {
            $namespace = Str::lower(basename($module));
            View::addNamespace($namespace, $module.'/Views');
        }
    }
}
