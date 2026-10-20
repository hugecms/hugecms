<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum OrderRefundStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case Status0 = 0;

    /**
     * 退款中
     */
    case Status1 = 1;

    /**
     * 已退款
     */
    case Status2 = 2;

    /**
     * 已拒绝
     */
    case Status3 = 3;
}
