<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum WechatMessageStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待发送
     */
    case Status0 = 0;

    /**
     * 成功
     */
    case Status1 = 1;

    /**
     * 失败
     */
    case Status2 = 2;
}
