<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 后台管理员认证模型（app/Models 手写，避免 gen:* 覆盖 Domains 产物导致登录链路断裂）
 * 与 App\Domains\Admin\Models\AdminUser 共用 admin_users 表：后者是生成器产物，供 CRUD 脚手架使用
 */
class AdminUser extends Authenticatable
{
    use HasApiTokens;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'status',
        'is_super',
        'last_login_time',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_time' => 'datetime',
            'status' => 'integer',
            'is_super' => 'integer',
        ];
    }
}
