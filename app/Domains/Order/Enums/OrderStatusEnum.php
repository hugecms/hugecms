<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum OrderStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =待支付
     */
    case Status0 = 0;

    /**
     * =已支付
     */
    case Status1 = 1;

    /**
     * =已发货
     */
    case Status2 = 2;

    /**
     * =已完成
     */
    case Status3 = 3;

    /**
     * =已取消
     */
    case Status4 = 4;
}
