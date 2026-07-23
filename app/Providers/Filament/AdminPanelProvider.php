<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetFilamentLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#0530ad',
            ])
            ->navigationGroups([
                NavigationGroup::make('内容管理')
                    ->icon(Heroicon::OutlinedDocumentText),
                NavigationGroup::make('媒体资源')
                    ->icon(Heroicon::OutlinedPhoto),
                NavigationGroup::make('运营工具')
                    ->icon(Heroicon::OutlinedSparkles),
                NavigationGroup::make('用户与权限')
                    ->icon(Heroicon::OutlinedUsers),
                NavigationGroup::make('数据报表')
                    ->icon(Heroicon::OutlinedChartPie),
                NavigationGroup::make('系统设置')
                    ->icon(Heroicon::OutlinedCog6Tooth),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                // 仪表盘 widget 由 discoverWidgets 自动注册，此处留空以移除默认 widget
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetFilamentLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
