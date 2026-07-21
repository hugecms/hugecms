<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaResource\Pages\ListMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = '媒体库';

    protected static ?string $modelLabel = '媒体';

    protected static ?string $pluralModelLabel = '媒体库';

    protected static ?string $recordTitleAttribute = 'name';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('预览')
                    ->state(fn (Media $record): string => $record->hasGeneratedConversion('thumb')
                        ? $record->getUrl('thumb')
                        : $record->getUrl())
                    ->square(),
                TextColumn::make('name')
                    ->label('文件名')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection_name')
                    ->label('集合')
                    ->badge()
                    ->sortable(),
                TextColumn::make('model_type')
                    ->label('关联模型')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('类型')
                    ->sortable(),
                TextColumn::make('human_readable_size')
                    ->label('大小')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('上传时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('collection_name')
                    ->label('集合')
                    ->options([
                        'cover' => '封面',
                        'avatar' => '头像',
                    ]),
                SelectFilter::make('mime_type')
                    ->label('类型')
                    ->options([
                        'image/jpeg' => 'JPEG',
                        'image/png' => 'PNG',
                        'image/webp' => 'WebP',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
        ];
    }
}
