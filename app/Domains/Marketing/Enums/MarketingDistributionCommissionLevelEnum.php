<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 层级枚举
 */
enum MarketingDistributionCommissionLevelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 一级
     */
    case Level1 = 1;

    /**
     * 二级
     */
    case Level2 = 2;
}
