<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 对象类型枚举
 */
enum SystemRiskRecordObjectTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 订单
     */
    case ObjectType1 = 1;

    /**
     * 优惠券
     */
    case ObjectType2 = 2;

    /**
     * 提现
     */
    case ObjectType3 = 3;

    /**
     * 售后
     */
    case ObjectType4 = 4;
}
