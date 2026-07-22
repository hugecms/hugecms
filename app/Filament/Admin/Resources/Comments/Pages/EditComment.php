<?php

namespace App\Filament\Admin\Resources\Comments\Pages;

use App\Filament\Admin\Resources\Comments\CommentResource;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;
}
