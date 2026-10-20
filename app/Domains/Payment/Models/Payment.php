<?php

declare(strict_types=1);

namespace App\Domains\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

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
        'user_id',
        'biz_type',
        'biz_id',
        'pay_method',
        'appid',
        'amount',
        'status',
        'trade_no',
        'paid_at',
    ];
}
