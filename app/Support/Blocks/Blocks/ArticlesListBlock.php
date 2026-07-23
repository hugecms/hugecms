<?php

namespace App\Support\Blocks\Blocks;

use App\Models\Category;
use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ArticlesListBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'articles_list';
    }

    public static function label(): string
    {
        return '文章列表';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedQueueList;
    }

    public static function schema(): array
    {
        return [
            Select::make('category_id')
                ->label('分类')
                ->options(fn () => Category::pluck('name', 'id'))
                ->native(false)
                ->searchable()
                ->nullable(),
            TextInput::make('count')
                ->label('显示数量')
                ->numeric()
                ->default(3)
                ->required(),
            Toggle::make('show_cover')
                ->label('显示封面')
                ->default(true),
            ...static::styleFields(),
        ];
    }
}
