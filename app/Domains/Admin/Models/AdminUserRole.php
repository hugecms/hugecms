<?php

declare(strict_types=1);

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_roles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'admin_user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'create_time',
    ];
}
