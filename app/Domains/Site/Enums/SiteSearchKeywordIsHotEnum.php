<?php

declare(strict_types=1);

namespace App\Domains\Site\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 人工推荐热词枚举
 */
enum SiteSearchKeywordIsHotEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsHot0 = 0;

    /**
     * 是
     */
    case IsHot1 = 1;
}
