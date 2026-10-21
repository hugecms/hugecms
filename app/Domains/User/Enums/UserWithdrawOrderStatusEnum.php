<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 提现单状态枚举
 */
enum UserWithdrawOrderStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待审核
     */
    case Pending = 10;

    /**
     * 审核通过
     */
    case Approved = 20;

    /**
     * 打款中
     */
    case Paying = 30;

    /**
     * 成功
     */
    case Success = 40;

    /**
     * 审核拒绝
     */
    case Rejected = 50;

    /**
     * 打款失败已回退
     */
    case Failed = 60;
}
