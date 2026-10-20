<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 类型枚举
 */
enum OrderInvoiceTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 电子普通发票
     */
    case Type1 = 1;

    /**
     * 纸质普通发票
     */
    case Type2 = 2;

    /**
     * 增值税专用发票
     */
    case Type3 = 3;
}
