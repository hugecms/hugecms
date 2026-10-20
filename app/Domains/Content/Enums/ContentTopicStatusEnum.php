<?php

declare(strict_types=1);

namespace App\Domains\Content\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ContentTopicStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 下线
     */
    case Status0 = 0;

    /**
     * 上线
     */
    case Status1 = 1;
}
