<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 业务类型枚举
 */
enum UserAccountLogBizTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 佣金入账
     */
    case BizType1 = 1;

    /**
     * 提现冻结
     */
    case BizType2 = 2;

    /**
     * 提现完成
     */
    case BizType3 = 3;

    /**
     * 提现失败回退
     */
    case BizType4 = 4;

    /**
     * 冲销扣回
     */
    case BizType5 = 5;

    /**
     * 余额消费冻结
     */
    case BizType6 = 6;

    /**
     * 余额消费完成
     */
    case BizType7 = 7;

    /**
     * 余额消费退回
     */
    case BizType8 = 8;

    /**
     * 充值入账
     */
    case BizType9 = 9;

    /**
     * 人工调整
     */
    case BizType10 = 10;
}
