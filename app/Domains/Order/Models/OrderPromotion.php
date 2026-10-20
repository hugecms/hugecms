<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPromotion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_promotions';

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
        'order_id',
        'type',
        'related_id',
        'name',
        'order_item_id',
        'amount',
        'points',
    ];
}
