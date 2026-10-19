<?php

declare(strict_types=1);

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_permissions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'permission_id',
        'create_time',
    ];
}
