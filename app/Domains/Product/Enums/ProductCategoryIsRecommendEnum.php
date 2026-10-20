<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 首页推荐枚举
 */
enum ProductCategoryIsRecommendEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsRecommend0 = 0;

    /**
     * 是
     */
    case IsRecommend1 = 1;
}
