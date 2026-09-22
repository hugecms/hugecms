<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatusEnum: int
{
    case Active = 1;

    case Inactive = 2;
}
