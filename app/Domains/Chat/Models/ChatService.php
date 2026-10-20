<?php

declare(strict_types=1);

namespace App\Domains\Chat\Models;

use Illuminate\Database\Eloquent\Model;

class ChatService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_services';

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
        'admin_user_id',
        'online_status',
        'max_sessions',
        'current_sessions',
    ];
}
