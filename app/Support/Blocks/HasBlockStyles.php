<?php

namespace App\Support\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

trait HasBlockStyles
{
    public static function styleFields(): array
    {
        return [
            Select::make('background_color')
                ->label('背景色')
                ->options([
                    'white' => '白色',
                    'gray' => '灰色',
                    'blue' => '蓝色',
                    'green' => '绿色',
                    'dark' => '深色',
                ])
                ->default('white')
                ->native(false),
            Select::make('padding_top')
                ->label('上内边距')
                ->options([
                    'sm' => '小',
                    'md' => '中',
                    'lg' => '大',
                    'xl' => '特大',
                ])
                ->default('md')
                ->native(false),
            Select::make('padding_bottom')
                ->label('下内边距')
                ->options([
                    'sm' => '小',
                    'md' => '中',
                    'lg' => '大',
                    'xl' => '特大',
                ])
                ->default('md')
                ->native(false),
            Toggle::make('container')
                ->label('限制在居中容器内')
                ->default(true),
        ];
    }
}
