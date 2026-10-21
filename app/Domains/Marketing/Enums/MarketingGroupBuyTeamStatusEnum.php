<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingGroupBuyTeamStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 拼团中
     */
    case Status1 = 1;

    /**
     * 已成团
     */
    case Status2 = 2;

    /**
     * 已解散(超时/取消)
     */
    case Status3 = 3;
}
