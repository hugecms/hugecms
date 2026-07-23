<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class CtaBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'cta';
    }

    public static function label(): string
    {
        return 'CTA 按钮';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedMegaphone;
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('标题')
                ->required()
                ->columnSpanFull(),
            Textarea::make('description')
                ->label('描述')
                ->rows(2)
                ->columnSpanFull(),
            TextInput::make('button_text')
                ->label('按钮文字')
                ->required(),
            TextInput::make('button_url')
                ->label('按钮链接')
                ->url()
                ->required(),
            Select::make('style')
                ->label('样式')
                ->options([
                    'primary' => '主色',
                    'secondary' => '辅色',
                ])
                ->default('primary')
                ->native(false),
            ...static::styleFields(),
        ];
    }
}
