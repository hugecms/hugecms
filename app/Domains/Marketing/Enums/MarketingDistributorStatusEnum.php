<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingDistributorStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case Status0 = 0;

    /**
     * 通过
     */
    case Status1 = 1;

    /**
     * 拒绝
     */
    case Status2 = 2;

    /**
     * 冻结
     */
    case Status3 = 3;
}
