<?php

declare(strict_types=1);

namespace App\Domains\Site\Models;

use Illuminate\Database\Eloquent\Model;

class SiteDailyStatistic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_daily_statistics';

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
        'stat_date',
        'pv',
        'uv',
        'new_users',
        'order_count',
        'order_amount',
        'pay_count',
        'pay_amount',
        'refund_count',
        'refund_amount',
    ];
}
