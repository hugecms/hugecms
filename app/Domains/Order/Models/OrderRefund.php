<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_refunds';

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
        'refund_no',
        'order_id',
        'payment_id',
        'type',
        'amount',
        'reason',
        'admin_note',
        'audit_time',
        'operator_id',
        'images',
        'return_express_company',
        'return_express_no',
        'status',
        'refunded_at',
    ];
}
