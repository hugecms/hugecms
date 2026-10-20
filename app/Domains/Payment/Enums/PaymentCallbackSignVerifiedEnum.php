<?php

declare(strict_types=1);

namespace App\Domains\Payment\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 验签结果枚举
 */
enum PaymentCallbackSignVerifiedEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未通过
     */
    case SignVerified0 = 0;

    /**
     * 通过
     */
    case SignVerified1 = 1;
}
