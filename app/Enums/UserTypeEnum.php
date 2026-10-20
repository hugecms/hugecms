<?php

declare(strict_types=1);

namespace App\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

enum UserTypeEnum: string implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 管理员
     */
    case AdminUser = 'admin_user';

    /**
     * 购买方
     */
    case User = 'user';
}
