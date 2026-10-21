<?php

declare(strict_types=1);

namespace App\Domains\System\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 申诉状态枚举
 */
enum SystemRiskRecordAppealStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 无
     */
    case AppealStatus0 = 0;

    /**
     * 申诉中
     */
    case AppealStatus1 = 1;

    /**
     * 申诉通过
     */
    case AppealStatus2 = 2;

    /**
     * 申诉驳回
     */
    case AppealStatus3 = 3;
}
