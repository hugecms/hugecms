<?php

declare(strict_types=1);

namespace App\Domains\Form\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 必填枚举
 */
enum FormFieldIsRequiredEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 否
     */
    case IsRequired0 = 0;

    /**
     * 是
     */
    case IsRequired1 = 1;
}
