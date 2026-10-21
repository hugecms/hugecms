<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingBargainRecordStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 砍价中
     */
    case Status1 = 1;

    /**
     * 到底价待下单
     */
    case Status2 = 2;

    /**
     * 已下单
     */
    case Status3 = 3;

    /**
     * 超时失败
     */
    case Status4 = 4;

    /**
     * 已取消
     */
    case Status5 = 5;
}
