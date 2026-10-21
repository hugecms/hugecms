<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 范围类型枚举
 */
enum MarketingCommissionRuleScopeTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 分类默认
     */
    case ScopeType1 = 1;

    /**
     * 商品覆盖
     */
    case ScopeType2 = 2;
}
