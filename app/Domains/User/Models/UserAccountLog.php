<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccountLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_account_logs';

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
        'biz_type',
        'amount',
        'balance_after',
        'frozen_after',
        'biz_no',
        'description',
        'create_time',
    ];
}
