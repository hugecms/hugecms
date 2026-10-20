<?php

declare(strict_types=1);

namespace App\Domains\Chat\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 回复类型枚举
 */
enum ChatBotRuleReplyTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 文本
     */
    case ReplyType1 = 1;

    /**
     * FAQ
     */
    case ReplyType2 = 2;

    /**
     * 转人工
     */
    case ReplyType3 = 3;
}
