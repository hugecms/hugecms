<?php

declare(strict_types=1);

namespace App\Domains\Chat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ChatSessionStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 进行中
     */
    case Status1 = 1;

    /**
     * 已结束
     */
    case Status2 = 2;
}
