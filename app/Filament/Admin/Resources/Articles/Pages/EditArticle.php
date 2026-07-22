<?php

namespace App\Filament\Admin\Resources\Articles\Pages;

use App\Filament\Admin\Resources\Articles\ArticleResource;
use App\Models\Media;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected ?int $mediaLibraryId = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->mediaLibraryId = $data['media_library_id'] ?? null;
        unset($data['media_library_id']);

        return $data;
    }

    protected function afterSave(): void
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
