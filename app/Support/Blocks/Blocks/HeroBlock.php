<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class HeroBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'hero';
    }

    public static function label(): string
    {
        return 'Hero 横幅';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedPhoto;
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('大标题')
                ->required()
                ->columnSpanFull(),
            TextInput::make('subtitle')
                ->label('副标题')
                ->columnSpanFull(),
            FileUpload::make('background_image')
                ->label('背景图')
                ->image()
                ->disk('public')
                ->directory('blocks')
                ->columnSpanFull(),
            TextInput::make('cta_text')
                ->label('按钮文字'),
            TextInput::make('cta_url')
                ->label('按钮链接')
                ->url()
                ->nullable(),
            ...static::styleFields(),
        ];
    }
}
