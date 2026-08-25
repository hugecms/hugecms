<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatusEnum: int
{
    case Enabled = 1;

    case Disabled = 0;

    public function label(): string
    {
        return $this === self::Enabled ? '启用' : '禁用';
    }
}
