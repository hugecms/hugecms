<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 有效期方式枚举
 */
enum MarketingCouponValidTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 固定区间(start_time/end_time)
     */
    case ValidType1 = 1;

    /**
     * 领取后N天
     */
    case ValidType2 = 2;
}
