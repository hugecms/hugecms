<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable([
    'name',
    'slug',
    'description',
    'sort_order',
])]
class MediaCategory extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name, language: 'zh');
            }
        });
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
