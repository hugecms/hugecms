<?php

namespace App\Enums;

enum MenuItemTarget: string
{
    case Self = '_self';
    case Blank = '_blank';

    public function getLabel(): string
    {
        return match ($this) {
            self::Self => '当前窗口',
            self::Blank => '新窗口',
        };
    }
}
