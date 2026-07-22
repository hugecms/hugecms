<?php

namespace App\Filament\Admin\Resources\Pages\Pages;

use App\Filament\Admin\Resources\Pages\PageResource;
use App\Models\Media;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

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
