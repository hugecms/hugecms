<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 积分流水业务类型枚举
 */
enum UserPointLogBizTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 签到奖励
     */
    case SignInReward = 1;

    /**
     * 下单抵扣
     */
    case OrderDeduct = 2;

    /**
     * 订单积分奖励
     */
    case OrderReward = 3;

    /**
     * 退款返还
     */
    case RefundReturn = 4;

    /**
     * 积分兑换
     */
    case Exchange = 5;

    /**
     * 人工调整
     */
    case AdminAdjust = 6;

    /**
     * 注册赠送
     */
    case RegisterGift = 7;
}
