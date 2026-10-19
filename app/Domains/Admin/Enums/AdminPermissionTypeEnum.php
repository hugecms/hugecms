<?php

declare(strict_types=1);

namespace App\Domains\Admin\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 类型枚举
 */
enum AdminPermissionTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * =菜单
     */
    case Type1 = 1;

    /**
     * =按钮
     */
    case Type2 = 2;
}
