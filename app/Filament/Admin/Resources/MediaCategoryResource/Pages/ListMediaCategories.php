<?php

namespace App\Filament\Admin\Resources\MediaCategoryResource\Pages;

use App\Filament\Admin\Resources\MediaCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMediaCategories extends ListRecords
{
    protected static string $resource = MediaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
