<?php

declare(strict_types=1);

namespace App\Domains\Content\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 置顶枚举
 */
enum ContentArticleIsTopEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =否
     */
    case IsTop0 = 0;

    /**
     * =是
     */
    case IsTop1 = 1;
}
