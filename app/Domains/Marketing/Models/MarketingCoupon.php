<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingCoupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marketing_coupons';

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
        'name',
        'type',
        'min_amount',
        'reduce_amount',
        'discount_rate',
        'receive_type',
        'total',
        'received_count',
        'per_limit',
        'valid_type',
        'valid_days',
        'start_time',
        'end_time',
        'status',
    ];
}
