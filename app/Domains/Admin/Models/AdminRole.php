<?php

declare(strict_types=1);

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_roles';

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
        'name',
        'code',
        'description',
        'sort',
        'status',
        'create_time',
        'update_time',
    ];
}
