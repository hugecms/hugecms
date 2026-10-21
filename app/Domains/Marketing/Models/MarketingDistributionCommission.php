<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingDistributionCommission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marketing_distribution_commissions';

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
        'distributor_id',
        'user_id',
        'order_id',
        'order_item_id',
        'level',
        'order_amount',
        'rate',
        'amount',
        'status',
        'settled_at',
        'settle_time',
        'reversal_record_id',
        'reversal_of_id',
    ];
}
