<?php

declare(strict_types=1);

namespace App\Domains\Form\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 需要登录枚举
 */
enum FormNeedLoginEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case NeedLogin0 = 0;

    /**
     * 是
     */
    case NeedLogin1 = 1;
}
