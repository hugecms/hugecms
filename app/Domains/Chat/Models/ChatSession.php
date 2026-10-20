<?php

declare(strict_types=1);

namespace App\Domains\Chat\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_sessions';

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
        'user_id',
        'server_id',
        'source_type',
        'source_id',
        'status',
        'user_last_read_id',
        'server_last_read_id',
        'rating',
        'rated_at',
        'assigned_at',
        'closed_at',
        'close_reason',
    ];
}
