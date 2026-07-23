<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class TwoColumnBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'two_column';
    }

    public static function label(): string
    {
        return '双栏';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedSquares2x2;
    }

    public static function schema(): array
    {
        return [
            RichEditor::make('left_content')
                ->label('左侧内容')
                ->required()
                ->columnSpan(1)
                ->fileAttachmentsDirectory('blocks'),
            FileUpload::make('right_image')
                ->label('右侧图片')
                ->image()
                ->disk('public')
                ->directory('blocks')
                ->columnSpan(1),
            Toggle::make('swap_on_mobile')
                ->label('移动端左右互换')
                ->default(false)
                ->columnSpanFull(),
            ...static::styleFields(),
        ];
    }
}
