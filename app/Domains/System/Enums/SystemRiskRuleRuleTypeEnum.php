<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 规则类型枚举
 */
enum SystemRiskRuleRuleTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 用户黑名单
     */
    case RuleType1 = 1;

    /**
     * 提现高频
     */
    case RuleType2 = 2;

    /**
     * 异常领券
     */
    case RuleType3 = 3;

    /**
     * 佣金套利
     */
    case RuleType4 = 4;

    /**
     * 休眠分级
     */
    case RuleType5 = 5;
}
