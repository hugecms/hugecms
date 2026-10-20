<?php

declare(strict_types=1);

namespace App\Domains\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscription extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_subscriptions';

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
        'email',
        'status',
        'token',
    ];
}
