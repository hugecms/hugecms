<?php

declare(strict_types=1);

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_carts';

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
        'user_id',
        'product_id',
        'sku_id',
        'product_name',
        'image',
        'price',
        'quantity',
        'checked',
        'create_time',
        'update_time',
    ];
}
