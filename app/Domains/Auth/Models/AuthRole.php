<?php

declare(strict_types=1);

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth_role';

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
    ];
}
