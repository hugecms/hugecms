<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WechatFan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wechat_fans';

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
        'openid',
        'unionid',
        'nickname',
        'avatar',
        'subscribed',
        'subscribed_at',
        'unsubscribed_at',
        'subscribe_scene',
        'tags',
        'user_id',
    ];
}
