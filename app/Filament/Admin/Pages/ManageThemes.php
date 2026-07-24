<?php

namespace App\Filament\Admin\Pages;

use App\Support\SiteSetting;
use App\Themes\ThemeRegistry;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageThemes extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = '系统设置';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = '主题管理';

    protected static ?string $title = '主题管理';

    protected string $view = 'filament.admin.pages.manage-themes';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function getViewData(): array
    {
        return [
            'themes' => app(ThemeRegistry::class)->all(),
            'activeId' => SiteSetting::get('active_theme', 'default'),
        ];
    }

    public function activate(string $themeId): void
    {
        $registry = app(ThemeRegistry::class);
        $theme = $registry->get($themeId);

        if ($theme === null) {
            Notification::make()
                ->danger()
                ->title('主题不存在')
                ->send();

            return;
        }

        SiteSetting::set('active_theme', $themeId);

        Notification::make()
            ->success()
            ->title('主题已启用')
            ->body("已切换到主题：{$theme->label}")
            ->send();
    }
}
