<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum MarketingAssistRecordStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 进行中
     */
    case Status1 = 1;

    /**
     * 已完成发奖
     */
    case Status2 = 2;

    /**
     * 已过期
     */
    case Status3 = 3;
}
