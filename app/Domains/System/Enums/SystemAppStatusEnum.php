<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum SystemAppStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 停用
     */
    case Status0 = 0;

    /**
     * 启用;停用即一键下线该端
     */
    case Status1 = 1;
}
