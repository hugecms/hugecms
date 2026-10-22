<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 取消类型枚举
 */
enum OrderCancelTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 用户取消
     */
    case CancelType1 = 1;

    /**
     * 超时未支付
     */
    case CancelType2 = 2;

    /**
     * 商家/系统取消
     */
    case CancelType3 = 3;
}
