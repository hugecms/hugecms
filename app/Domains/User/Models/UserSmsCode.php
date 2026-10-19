<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserSmsCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_sms_codes';

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
        'phone',
        'code',
        'scene',
        'expire_at',
        'used_at',
        'ip',
        'create_time',
    ];
}
