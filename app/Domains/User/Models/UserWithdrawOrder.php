<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserWithdrawOrder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_withdraw_orders';

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
        'withdraw_no',
        'user_id',
        'amount',
        'channel',
        'channel_order_no',
        'account_type',
        'account_name',
        'account_no',
        'status',
        'user_note',
        'admin_note',
        'fail_reason',
        'audit_at',
        'paid_at',
    ];
}
