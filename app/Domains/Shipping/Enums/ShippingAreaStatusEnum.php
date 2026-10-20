<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ShippingAreaStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 停用
     */
    case Status0 = 0;

    /**
     * 启用
     */
    case Status1 = 1;
}
