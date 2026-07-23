<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'content',
    'status',
    'published_at',
    'user_id',
    'is_pinned',
    'is_recommended',
    'seo_title',
    'seo_description',
    'seo_keywords',
])]
class Article extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (self $article) {
            if (blank($article->slug)) {
                $article->slug = Str::slug($article->title, language: 'zh');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => ContentStatus::class,
            'is_pinned' => 'boolean',
            'is_recommended' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->crop(150, 150)
                    ->performOnCollections('cover');
                $this->addMediaConversion('medium')
                    ->width(600)
                    ->height(400)
                    ->performOnCollections('cover');
                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(800)
                    ->performOnCollections('cover');
                $this->addMediaConversion('og-image')
                    ->crop(1200, 630)
                    ->performOnCollections('cover');
            });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }
}
