<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum UserWithdrawOrderStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case Status10 = 10;

    /**
     * 审核通过
     */
    case Status20 = 20;

    /**
     * 打款中
     */
    case Status30 = 30;

    /**
     * 成功
     */
    case Status40 = 40;

    /**
     * 审核拒绝
     */
    case Status50 = 50;

    /**
     * 打款失败已回退
     */
    case Status60 = 60;
}
