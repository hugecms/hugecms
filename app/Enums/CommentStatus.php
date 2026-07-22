<?php

namespace App\Enums;

enum CommentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Deleted = 'deleted';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => '待审核',
            self::Approved => '已通过',
            self::Rejected => '已拒绝',
            self::Deleted => '已删除',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Deleted => 'gray',
        };
    }
}
