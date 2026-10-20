<?php

declare(strict_types=1);

namespace App\Domains\Content\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ContentCommentStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审
     */
    case Status0 = 0;

    /**
     * 通过
     */
    case Status1 = 1;

    /**
     * 拒绝
     */
    case Status2 = 2;
}
