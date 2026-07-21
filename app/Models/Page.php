<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use NodeTrait;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'user_id',
        'parent_id',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
