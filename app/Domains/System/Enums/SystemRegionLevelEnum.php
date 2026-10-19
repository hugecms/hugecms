<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 层级枚举
 */
enum SystemRegionLevelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =省
     */
    case Level1 = 1;

    /**
     * =市
     */
    case Level2 = 2;

    /**
     * =区县
     */
    case Level3 = 3;
}
