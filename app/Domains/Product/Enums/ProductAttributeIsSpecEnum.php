<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 是否规格属性枚举
 */
enum ProductAttributeIsSpecEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsSpec0 = 0;

    /**
     * 是(参与SKU组合)
     */
    case IsSpec1 = 1;
}
