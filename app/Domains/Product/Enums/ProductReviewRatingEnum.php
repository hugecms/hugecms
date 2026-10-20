<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 评分枚举
 */
enum ProductReviewRatingEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 5星
     */
    case Rating1 = 1;
}
