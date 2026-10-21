<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 资金流水业务类型枚举
 */
enum UserAccountLogBizTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 佣金入账
     */
    case CommissionCredit = 1;

    /**
     * 提现冻结
     */
    case WithdrawFreeze = 2;

    /**
     * 提现完成
     */
    case WithdrawSettled = 3;

    /**
     * 提现失败回退
     */
    case WithdrawRefund = 4;

    /**
     * 冲销扣回
     */
    case ReversalDeduct = 5;

    /**
     * 余额消费冻结
     */
    case ConsumeFreeze = 6;

    /**
     * 余额消费完成
     */
    case ConsumeSettled = 7;

    /**
     * 余额消费退回
     */
    case ConsumeRefund = 8;

    /**
     * 充值入账
     */
    case RechargeCredit = 9;

    /**
     * 人工调整
     */
    case AdminAdjust = 10;
}
