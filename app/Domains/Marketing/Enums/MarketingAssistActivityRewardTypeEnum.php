<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 奖励类型枚举
 */
enum MarketingAssistActivityRewardTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 优惠券
     */
    case RewardType1 = 1;

    /**
     * 积分
     */
    case RewardType2 = 2;
}
