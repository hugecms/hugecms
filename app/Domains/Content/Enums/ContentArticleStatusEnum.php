<?php

declare(strict_types=1);

namespace App\Domains\Content\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum ContentArticleStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 草稿
     */
    case Status0 = 0;

    /**
     * 发布
     */
    case Status1 = 1;

    /**
     * 下架
     */
    case Status2 = 2;
}
