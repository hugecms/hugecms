<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum UserFundApplicationStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case Status0 = 0;

    /**
     * 已到账
     */
    case Status1 = 1;

    /**
     * 已拒绝
     */
    case Status2 = 2;
}
