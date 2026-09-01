<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WechatUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wechat_user';

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
    ];
}
