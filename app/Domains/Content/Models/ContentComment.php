<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class ContentComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_comments';

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
        'target_type',
        'target_id',
        'parent_id',
        'content',
        'status',
        'ip',
        'create_time',
        'update_time',
    ];
}
