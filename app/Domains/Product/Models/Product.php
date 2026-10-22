<?php

declare(strict_types=1);

namespace App\Domains\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

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
        'category_id',
        'brand_id',
        'type_id',
        'freight_template_id',
        'name',
        'sn',
        'subtitle',
        'cover',
        'images',
        'content',
        'price',
        'market_price',
        'sales',
        'status',
        'sort',
    ];
}
