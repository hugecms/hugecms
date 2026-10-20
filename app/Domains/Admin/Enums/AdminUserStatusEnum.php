<?php

declare(strict_types=1);

namespace App\Domains\Admin\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum AdminUserStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 禁用
     */
    case Status0 = 0;

    /**
     * 正常
     */
    case Status1 = 1;
}
