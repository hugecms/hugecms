<?php

declare(strict_types=1);

namespace App\Domains\Chat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 满意度评分枚举
 */
enum ChatSessionRatingEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未评
     */
    case Rating0 = 0;

    /**
     * 1星
     */
    case Rating1 = 1;

    /**
     * 2星
     */
    case Rating2 = 2;

    /**
     * 3星
     */
    case Rating3 = 3;

    /**
     * 4星
     */
    case Rating4 = 4;

    /**
     * 5星
     */
    case Rating5 = 5;
}
