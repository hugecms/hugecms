<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 认证边界模型（Guard Provider，见 config/auth.php）。
 *
 * 与 DevTools 生成的领域模型 App\Domains\User\Models\User 映射同一张 users 表：
 * 本模型只服务认证体系（登录态、密码哈希、通知），领域 CRUD 走生成的领域模型，
 * 二者职责分离，符合军规 3.2 的防腐隔离约定（生成代码不承载手写认证逻辑）。
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
