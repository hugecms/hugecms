<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 状态枚举
 */
enum OrderInvoiceStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待开票
     */
    case Status0 = 0;

    /**
     * 已开票
     */
    case Status1 = 1;

    /**
     * 已作废
     */
    case Status2 = 2;
}
