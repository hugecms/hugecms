<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 默认地址枚举
 */
enum UserAddressIsDefaultEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsDefault0 = 0;

    /**
     * 是
     */
    case IsDefault1 = 1;
}
