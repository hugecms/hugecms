<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping_areas';

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
        'shipping_id',
        'name',
        'fee_config',
        'status',
    ];
}
