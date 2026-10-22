<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

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
        'product_id',
        'sku_id',
        'product_name',
        'sku_specs',
        'sku_no',
        'sku_name',
        'sku_image',
        'image',
        'price',
        'quantity',
        'total_amount',
        'create_time',
    ];
}
