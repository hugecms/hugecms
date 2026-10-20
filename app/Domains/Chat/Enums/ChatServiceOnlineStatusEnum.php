<?php

declare(strict_types=1);

namespace App\Domains\Chat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 在线状态枚举
 */
enum ChatServiceOnlineStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 离线
     */
    case OnlineStatus0 = 0;

    /**
     * 在线
     */
    case OnlineStatus1 = 1;

    /**
     * 忙碌
     */
    case OnlineStatus2 = 2;
}
