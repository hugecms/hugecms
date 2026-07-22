<?php

namespace App\Models;

use App\Enums\CommentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'content',
    'status',
    'article_id',
    'user_id',
    'parent_id',
    'guest_name',
    'guest_email',
])]
class Comment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => CommentStatus::class,
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getAuthorAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? '匿名';
    }
}
