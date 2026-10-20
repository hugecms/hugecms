<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 关注状态枚举
 */
enum WechatFanSubscribedEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 已取关
     */
    case Subscribed0 = 0;

    /**
     * 已关注
     */
    case Subscribed1 = 1;
}
