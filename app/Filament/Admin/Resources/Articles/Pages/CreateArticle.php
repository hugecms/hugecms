<?php

namespace App\Filament\Admin\Resources\Articles\Pages;

use App\Filament\Admin\Resources\Articles\ArticleResource;
use App\Models\Media;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected ?int $mediaLibraryId = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->mediaLibraryId = $data['media_library_id'] ?? null;
        unset($data['media_library_id']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! $this->mediaLibraryId) {
            return;
        }

        $media = Media::find($this->mediaLibraryId);

        if (! $media) {
            return;
        }

        $this->record->addMedia($media->getPath())
            ->preservingOriginal()
            ->toMediaCollection('cover');
    }
}
