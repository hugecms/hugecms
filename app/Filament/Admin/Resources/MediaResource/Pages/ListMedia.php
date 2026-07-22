<?php

namespace App\Filament\Admin\Resources\MediaResource\Pages;

use App\Filament\Admin\Resources\MediaResource;
use App\Models\MediaLibrary;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('上传媒体')
                ->form([
                    FileUpload::make('files')
                        ->label('选择文件')
                        ->multiple()
                        ->image()
                        ->disk('public')
                        ->directory('media-library')
                        ->helperText('支持批量上传图片')
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    $library = MediaLibrary::singleton();

                    if (empty($data['files'])) {
                        return;
                    }

                    foreach ($data['files'] as $file) {
                        $library->addMediaFromDisk($file, 'public')->toMediaCollection('media_library');
                    }
                })
                ->successNotificationTitle('上传成功'),
        ];
    }
}
