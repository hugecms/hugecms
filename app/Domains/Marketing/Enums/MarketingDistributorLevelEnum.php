<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 分销员等级枚举
 */
enum MarketingDistributorLevelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 普通
     */
    case Level1 = 1;

    /**
     * 高级
     */
    case Level2 = 2;
}
