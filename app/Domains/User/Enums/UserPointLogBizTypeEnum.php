<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 业务类型枚举
 */
enum UserPointLogBizTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 签到奖励
     */
    case BizType1 = 1;

    /**
     * 下单抵扣
     */
    case BizType2 = 2;

    /**
     * 订单积分奖励
     */
    case BizType3 = 3;

    /**
     * 退款返还
     */
    case BizType4 = 4;

    /**
     * 积分兑换
     */
    case BizType5 = 5;

    /**
     * 人工调整
     */
    case BizType6 = 6;

    /**
     * 注册赠送
     */
    case BizType7 = 7;

    /**
     * 活动奖励(助力等)
     */
    case BizType8 = 8;
}
