<?php

namespace App\Filament\Admin\Resources\OperationLogs\Pages;

use App\Filament\Admin\Resources\OperationLogs\OperationLogResource;
use Filament\Resources\Pages\ListRecords;

class ListOperationLogs extends ListRecords
{
    protected static string $resource = OperationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
