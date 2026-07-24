<?php

use App\Themes\Theme;
use App\Themes\ThemeResolver;

if (! function_exists('theme')) {
    function theme(): Theme
    {
        if (app()->bound('theme.active')) {
            return app('theme.active');
        }

        return app(ThemeResolver::class)->active();
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Build a public URL for a static asset inside the active theme
     * (css/js/images/fonts), served by ThemeAssetController.
     */
    function theme_asset(string $path): string
    {
        return route('theme.asset', ['theme' => theme()->id, 'path' => ltrim($path, '/')]);
    }
}
