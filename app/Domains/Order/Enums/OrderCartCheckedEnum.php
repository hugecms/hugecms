<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 结算勾选枚举
 */
enum OrderCartCheckedEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case Checked0 = 0;

    /**
     * 是
     */
    case Checked1 = 1;
}
