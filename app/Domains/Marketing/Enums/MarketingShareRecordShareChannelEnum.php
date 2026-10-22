<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 分享渠道枚举
 */
enum MarketingShareRecordShareChannelEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 小程序卡片
     */
    case ShareChannel1 = 1;

    /**
     * 海报图片
     */
    case ShareChannel2 = 2;

    /**
     * 复制链接
     */
    case ShareChannel3 = 3;

    /**
     * 朋友圈/社群
     */
    case ShareChannel4 = 4;
}
