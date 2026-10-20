<?php

declare(strict_types=1);

namespace App\Domains\Payment\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 处理状态枚举
 */
enum PaymentCallbackStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未处理
     */
    case Status0 = 0;

    /**
     * 已处理
     */
    case Status1 = 1;

    /**
     * 异常忽略
     */
    case Status2 = 2;
}
