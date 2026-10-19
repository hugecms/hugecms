<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 性别枚举
 */
enum UserProfileGenderEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =未知
     */
    case Gender0 = 0;

    /**
     * =男
     */
    case Gender1 = 1;

    /**
     * =女
     */
    case Gender2 = 2;
}
