<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ImageBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'image';
    }

    public static function label(): string
    {
        return '图片';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedPhoto;
    }

    public static function schema(): array
    {
        return [
            FileUpload::make('image')
                ->label('图片')
                ->image()
                ->disk('public')
                ->directory('blocks')
                ->required()
                ->columnSpanFull(),
            TextInput::make('alt')
                ->label('Alt 文本')
                ->nullable(),
            TextInput::make('link')
                ->label('链接')
                ->url()
                ->nullable(),
            ...static::styleFields(),
        ];
    }
}
