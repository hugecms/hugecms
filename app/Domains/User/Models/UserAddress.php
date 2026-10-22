<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';

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
        'receiver_name',
        'receiver_phone',
        'receiver_region',
        'receiver_province',
        'receiver_city',
        'receiver_district',
        'receiver_province_code',
        'receiver_city_code',
        'receiver_district_code',
        'receiver_address',
        'is_default',
    ];
}
