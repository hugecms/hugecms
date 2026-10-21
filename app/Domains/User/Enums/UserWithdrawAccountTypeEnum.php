<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 提现收款方式枚举
 */
enum UserWithdrawAccountTypeEnum: string implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 银行卡
     */
    case Bank = 'bank';

    /**
     * 微信
     */
    case Wechat = 'wechat';

    /**
     * 支付宝
     */
    case Alipay = 'alipay';
}
