<?php

namespace App\Support\Blocks\Blocks;

use App\Support\Blocks\BlockContract;
use App\Support\Blocks\HasBlockStyles;
use Filament\Forms\Components\RichEditor;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class RichTextBlock extends BlockContract
{
    use HasBlockStyles;

    public static function name(): string
    {
        return 'rich_text';
    }

    public static function label(): string
    {
        return '富文本';
    }

    public static function icon(): string|\BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedDocumentText;
    }

    public static function schema(): array
    {
        return [
            RichEditor::make('content')
                ->label('内容')
                ->required()
                ->columnSpanFull()
                ->fileAttachmentsDirectory('blocks'),
            ...static::styleFields(),
        ];
    }
}
