<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'description',
])]
class Tag extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (self $tag) {
            if (blank($tag->slug)) {
                $tag->slug = Str::slug($tag->name, language: 'zh');
            }
        });
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }
}
