<?php

namespace App\Filament\Admin\Resources\MediaCategoryResource\Pages;

use App\Filament\Admin\Resources\MediaCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMediaCategory extends EditRecord
{
    protected static string $resource = MediaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
