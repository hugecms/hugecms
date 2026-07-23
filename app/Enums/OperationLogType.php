<?php

namespace App\Enums;

enum OperationLogType: string
{
    case Login = 'login';
    case Logout = 'logout';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Restore = 'restore';
    case ForceDelete = 'force_delete';
    case Publish = 'publish';

    public function getLabel(): string
    {
        return match ($this) {
            self::Login => '登录',
            self::Logout => '登出',
            self::Create => '新增',
            self::Update => '编辑',
            self::Delete => '删除',
            self::Restore => '恢复',
            self::ForceDelete => '彻底删除',
            self::Publish => '发布',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Login, self::Logout => 'gray',
            self::Create => 'success',
            self::Update => 'info',
            self::Publish => 'primary',
            self::Delete, self::ForceDelete => 'danger',
            self::Restore => 'warning',
        };
    }
}
