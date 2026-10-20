<?php

declare(strict_types=1);

namespace App\Domains\Form\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 处理状态枚举
 */
enum FormSubmissionStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 未处理
     */
    case Status0 = 0;

    /**
     * 已处理
     */
    case Status1 = 1;
}
