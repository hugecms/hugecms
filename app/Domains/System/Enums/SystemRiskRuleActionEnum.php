<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 处置动作枚举
 */
enum SystemRiskRuleActionEnum: int implements EnumMethodInterface
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
