<?php

declare(strict_types=1);

namespace App\Domains\Email\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum EmailSubscriptionStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 已退订
     */
    case Status0 = 0;

    /**
     * 订阅中
     */
    case Status1 = 1;
}
