<?php

namespace App\Enums;

enum ContentStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Offline = 'offline';
    case Pending = 'pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => '草稿',
            self::Published => '已发布',
            self::Offline => '已下线',
            self::Pending => '待审核',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::Offline => 'danger',
            self::Pending => 'warning',
        };
    }
}
