<?php

use App\Support\Blocks\Blocks\ArticlesListBlock;
use App\Support\Blocks\Blocks\CtaBlock;
use App\Support\Blocks\Blocks\DividerBlock;
use App\Support\Blocks\Blocks\HeroBlock;
use App\Support\Blocks\Blocks\ImageBlock;
use App\Support\Blocks\Blocks\RichTextBlock;
use App\Support\Blocks\Blocks\TwoColumnBlock;

return [
    'blocks' => [
        HeroBlock::class,
        RichTextBlock::class,
        ImageBlock::class,
        TwoColumnBlock::class,
        ArticlesListBlock::class,
        CtaBlock::class,
        DividerBlock::class,
    ],
];
