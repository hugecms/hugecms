<?php

namespace App\Support\Blocks;

use BackedEnum;
use Filament\Forms\Components\Builder\Block;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;

abstract class BlockContract
{
    abstract public static function name(): string;

    abstract public static function label(): string;

    public static function icon(): string|BackedEnum|Htmlable|null
    {
        return null;
    }

    /**
     * @return array<int, Component>
     */
    abstract public static function schema(): array;

    public static function view(): string
    {
        return 'blocks.'.static::name();
    }

    public static function toFilamentBlock(): Block
    {
        $block = Block::make(static::name())
            ->label(static::label())
            ->schema(static::schema());

        if (static::icon() !== null) {
            $block->icon(static::icon());
        }

        return $block;
    }
}
