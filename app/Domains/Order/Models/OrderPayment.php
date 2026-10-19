<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_payments';

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
        'payment_no',
        'order_id',
        'pay_method',
        'amount',
        'status',
        'trade_no',
        'paid_at',
        'create_time',
        'update_time',
    ];
}
