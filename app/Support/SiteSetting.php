<?php

namespace App\Support;

use App\Models\Setting;

class SiteSetting
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return Setting::where([
            'group' => 'site',
            'key' => $key,
        ])->value('value') ?? $default;
    }

    public static function set(string $key, mixed $value): Setting
    {
        return Setting::updateOrCreate(
            ['group' => 'site', 'key' => $key],
            ['value' => $value],
        );
    }
}
