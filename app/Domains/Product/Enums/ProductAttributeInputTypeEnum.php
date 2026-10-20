<?php

declare(strict_types=1);

namespace App\Domains\Product\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 录入方式枚举
 */
enum ProductAttributeInputTypeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 手工输入
     */
    case InputType1 = 1;

    /**
     * 列表选择
     */
    case InputType2 = 2;
}
