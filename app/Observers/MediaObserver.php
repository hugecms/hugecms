<?php

namespace App\Observers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\MediaCollections\Models\Observers\MediaObserver as BaseMediaObserver;

class MediaObserver extends BaseMediaObserver
{
    public function created(Media $media): void
    {
        if (! str_starts_with($media->mime_type, 'image/')) {
            return;
        }

        $path = $media->getPath();

        if (! file_exists($path)) {
            return;
        }

        $dimensions = @getimagesize($path);

        if ($dimensions === false) {
            return;
        }

        $media->setCustomProperty('width', $dimensions[0]);
        $media->setCustomProperty('height', $dimensions[1]);
        $media->saveQuietly();
    }
}
