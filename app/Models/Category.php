<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use NodeTrait;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

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

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }
}
