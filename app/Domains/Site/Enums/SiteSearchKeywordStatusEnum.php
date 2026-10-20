<?php

declare(strict_types=1);

namespace App\Domains\Site\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum SiteSearchKeywordStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 屏蔽
     */
    case Status0 = 0;

    /**
     * 正常
     */
    case Status1 = 1;
}
