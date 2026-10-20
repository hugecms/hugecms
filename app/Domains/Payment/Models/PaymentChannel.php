<?php

declare(strict_types=1);

namespace App\Domains\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_channels';

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
        'code',
        'appid',
        'name',
        'config',
        'status',
        'sort',
    ];
}
