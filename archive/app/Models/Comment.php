<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

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
        'content_id',
        'user_id',
        'parent_id',
        'reply_to_user_id',
        'author_name',
        'author_email',
        'author_url',
        'content',
        'ip',
        'user_agent',
        'status',
        'like_count',
    ];
}
