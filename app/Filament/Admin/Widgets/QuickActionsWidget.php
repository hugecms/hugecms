<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Articles\ArticleResource;
use App\Filament\Admin\Resources\Comments\CommentResource;
use App\Filament\Admin\Resources\Pages\PageResource;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.quick-actions-widget';

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = '快捷操作';

    public function getActions(): array
    {
        return [
            [
                'label' => '新增文章',
                'icon' => Heroicon::OutlinedDocumentPlus,
                'url' => ArticleResource::getUrl('create'),
                'color' => 'primary',
            ],
            [
                'label' => '新增页面',
                'icon' => Heroicon::OutlinedDocumentPlus,
                'url' => PageResource::getUrl('create'),
                'color' => 'primary',
            ],
            [
                'label' => '查看待办',
                'icon' => Heroicon::OutlinedClipboardDocumentList,
                'url' => CommentResource::getUrl('index'),
                'color' => 'warning',
            ],
        ];
    }
}
