<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ProductStockReminderStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待提醒
     */
    case Status0 = 0;

    /**
     * 已提醒
     */
    case Status1 = 1;

    /**
     * 已取消
     */
    case Status2 = 2;
}
