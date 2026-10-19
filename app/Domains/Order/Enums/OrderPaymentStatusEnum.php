<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum OrderPaymentStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =待支付
     */
    case Status0 = 0;

    /**
     * =成功
     */
    case Status1 = 1;

    /**
     * =失败
     */
    case Status2 = 2;
}
