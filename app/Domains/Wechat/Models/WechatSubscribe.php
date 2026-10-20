<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WechatSubscribe extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wechat_subscribes';

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
        'appid',
        'user_id',
        'template_id',
        'times',
        'remain',
        'last_subscribed_at',
    ];
}
