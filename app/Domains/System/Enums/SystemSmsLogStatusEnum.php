<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum SystemSmsLogStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 发送中
     */
    case Status0 = 0;

    /**
     * 成功
     */
    case Status1 = 1;

    /**
     * 失败
     */
    case Status2 = 2;
}
