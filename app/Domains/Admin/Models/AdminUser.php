<?php

declare(strict_types=1);

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
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
        'last_login_time',
        'last_login_ip',
    ];
}
