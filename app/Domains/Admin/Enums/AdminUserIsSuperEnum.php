<?php

declare(strict_types=1);

namespace App\Domains\Admin\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 超级管理员枚举
 */
enum AdminUserIsSuperEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsSuper0 = 0;

    /**
     * 是;超管不受角色权限限制
     */
    case IsSuper1 = 1;
}
