<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WechatMessage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wechat_messages';

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
        'scene',
        'related_id',
        'openid',
        'content',
        'status',
        'error',
        'sent_at',
        'create_time',
    ];
}
