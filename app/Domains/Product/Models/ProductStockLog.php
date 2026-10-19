<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_stock_logs';

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
        'product_id',
        'sku_id',
        'change_type',
        'change_quantity',
        'before_stock',
        'after_stock',
        'related_id',
        'user_type',
        'user_id',
        'remark',
        'create_time',
    ];
}
