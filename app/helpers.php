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

if (! function_exists('theme_vite_entries')) {
    function theme_vite_entries(): array
    {
        $theme = theme();
        $entries = [];

        if (file_exists($theme->cssPath())) {
            $entries[] = $theme->cssEntry();
        }

        if (file_exists($theme->jsPath())) {
            $entries[] = $theme->jsEntry();
        }

        return $entries;
    }
}
