<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 结果枚举
 */
enum SystemOperationLogStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =失败
     */
    case Status0 = 0;

    /**
     * =成功
     */
    case Status1 = 1;
}
