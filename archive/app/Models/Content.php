<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contents';

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
        'model_id',
        'title',
        'slug',
        'author_id',
        'status',
        'visibility',
        'password',
        'views',
        'comment_count',
        'sort',
        'is_top',
        'published_at',
        'audit_status',
        'audit_remark',
        'auditor_id',
        'audited_at',
    ];
}
