<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SitemapCacheObserver
{
    public function saved(Page|Article|Category|Setting $model): void
    {
        $this->clear();
    }

    public function deleted(Page|Article|Category|Setting $model): void
    {
        $this->clear();
    }

    protected function clear(): void
    {
        Cache::forget('sitemap.xml');
    }
}
