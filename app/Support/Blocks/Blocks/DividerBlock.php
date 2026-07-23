<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class DividerBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'divider';
    }

    public static function label(): string
    {
        return '分割线';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedMinus;
    }

    public static function schema(): array
    {
        return [
            Select::make('style')
                ->label('样式')
                ->options([
                    'line' => '实线',
                    'dots' => '虚点',
                    'gradient' => '渐变',
                ])
                ->default('line')
                ->native(false),
            Select::make('spacing')
                ->label('间距')
                ->options([
                    'sm' => '小',
                    'md' => '中',
                    'lg' => '大',
                ])
                ->default('md')
                ->native(false),
            ...static::styleFields(),
        ];
    }
}
