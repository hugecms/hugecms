<?php

namespace App\Themes;

use App\Support\SiteSetting;
use Illuminate\Http\Request;

class ThemeResolver
{
    public function __construct(
        protected ThemeRegistry $registry,
        protected ?Request $request = null,
    ) {}

    public function active(): Theme
    {
        $request = $this->request ?? request();
        $previewId = $request?->query('theme');

        if (is_string($previewId) && $this->canPreview()) {
            $preview = $this->registry->get($previewId);

            if ($preview !== null) {
                return $preview;
            }
        }

        $activeId = SiteSetting::get('active_theme', 'default');
        $active = $this->registry->get($activeId);

        if ($active !== null) {
            return $active;
        }

        $default = $this->registry->get('default');

        if ($default !== null) {
            return $default;
        }

        throw new \RuntimeException('No themes are available.');
    }

    public function default(): Theme
    {
        $default = $this->registry->get('default');

        if ($default === null) {
            throw new \RuntimeException('Default theme is not available.');
        }

        return $default;
    }

    protected function canPreview(): bool
    {
        $user = auth()->user();

        return $user !== null && $user->hasRole('super_admin');
    }
}
