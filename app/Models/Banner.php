<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'title',
    'link',
    'sort_order',
    'status',
    'start_at',
    'end_at',
])]
class Banner extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'status' => PublishStatus::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->crop(150, 150)
                    ->performOnCollections('banner_image');
                $this->addMediaConversion('medium')
                    ->width(800)
                    ->height(400)
                    ->performOnCollections('banner_image');
            });
    }
}
