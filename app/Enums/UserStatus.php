<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => '正常',
            self::Disabled => '已禁用',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Disabled => 'danger',
        };
    }
}
