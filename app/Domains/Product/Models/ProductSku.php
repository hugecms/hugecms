<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_skus';

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
        'specs',
        'sku_no',
        'barcode',
        'image',
        'price',
        'line_price',
        'cost_price',
        'weight',
        'stock',
        'warn_stock',
        'status',
    ];
}
