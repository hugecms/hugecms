<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingUserCouponStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未使用
     */
    case Status0 = 0;

    /**
     * 已使用
     */
    case Status1 = 1;

    /**
     * 已过期
     */
    case Status2 = 2;
}
