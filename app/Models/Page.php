<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'title',
    'slug',
    'content',
    'blocks',
    'template',
    'status',
    'user_id',
    'parent_id',
    'seo_title',
    'seo_description',
    'seo_keywords',
])]
class Page extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use NodeTrait;
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (self $page) {
            if (blank($page->slug)) {
                $page->slug = Str::slug($page->title, language: 'zh');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'blocks' => 'array',
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
