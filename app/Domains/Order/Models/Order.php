<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'order_no',
        'user_id',
        'total_amount',
        'freight_amount',
        'discount_amount',
        'pay_amount',
        'status',
        'refund_status',
        'receiver_name',
        'receiver_phone',
        'receiver_region',
        'receiver_address',
        'buyer_remark',
        'pay_time',
        'deliver_time',
        'finish_time',
        'cancel_time',
        'cancel_reason',
    ];
}
