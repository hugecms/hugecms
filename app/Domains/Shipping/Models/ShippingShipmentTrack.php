<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingShipmentTrack extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping_shipment_tracks';

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
        'shipment_id',
        'accept_time',
        'location',
        'description',
        'create_time',
    ];
}
