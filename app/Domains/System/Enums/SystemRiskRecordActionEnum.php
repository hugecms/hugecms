<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 处置快照枚举
 */
enum SystemRiskRecordActionEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 拦截
     */
    case Action1 = 1;

    /**
     * 标记放行
     */
    case Action2 = 2;
}
