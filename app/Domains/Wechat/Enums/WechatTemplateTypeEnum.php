<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 模板类型枚举
 */
enum WechatTemplateTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 公众号模板消息
     */
    case Type1 = 1;

    /**
     * 小程序订阅消息
     */
    case Type2 = 2;
}
