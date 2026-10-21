<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_profiles';

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
        'user_id',
        'nickname',
        'avatar',
        'gender',
        'birthday',
        'bio',
        'location',
    ];
}
