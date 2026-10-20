<?php

declare(strict_types=1);

namespace App\Domains\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCallback extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_callbacks';

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
        'payment_id',
        'channel',
        'event_type',
        'notify_id',
        'trade_no',
        'raw_content',
        'sign_verified',
        'status',
        'processed_at',
        'create_time',
    ];
}
