<?php

namespace App\Support\Blocks;

use App\Models\Page;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class BlockRegistry
{
    /**
     * @return array<int, Block>
     */
    public static function forBuilder(): array
    {
        return array_map(
            fn (string $class) => self::resolve($class)->toFilamentBlock(),
            self::blockClasses()
        );
    }

    public static function renderPage(Page $page): string
    {
        if (blank($page->blocks) && filled($page->content)) {
            return self::render([
                ['type' => 'rich_text', 'data' => ['content' => $page->content]],
            ]);
        }

        return self::render($page->blocks ?? []);
    }

    /**
     * @param  array<int, array{type: string, data: array<string, mixed>}>  $blocks
     */
    public static function render(array $blocks): string
    {
        $output = '';

        foreach ($blocks as $block) {
            $type = $block['type'] ?? null;
            $data = $block['data'] ?? [];

            if (! is_string($type) || ! view()->exists('blocks.'.$type)) {
                continue;
            }

            $output .= Blade::render('@include("blocks.'.$type.'", ["data" => $data])', ['data' => $data]);
        }

        return $output;
    }

    /**
     * @return array<int, class-string<BlockContract>>
     */
    public static function blockClasses(): array
    {
        return Config::get('blocks.blocks', []);
    }

    /**
     * @param  class-string  $class
     */
    protected static function resolve(string $class): BlockContract
    {
        if (! is_subclass_of($class, BlockContract::class)) {
            throw new InvalidArgumentException("Block class [{$class}] must extend ".BlockContract::class);
        }

        return new $class;
    }
}
