<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingDistributionCommissionStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待结算
     */
    case Status0 = 0;

    /**
     * 已结算
     */
    case Status1 = 1;

    /**
     * 已失效(退款)
     */
    case Status2 = 2;
}
