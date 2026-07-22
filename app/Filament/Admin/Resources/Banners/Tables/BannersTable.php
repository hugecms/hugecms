<?php

namespace App\Filament\Admin\Resources\Banners\Tables;

use App\Enums\PublishStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner_image')
                    ->label('图片')
                    ->conversion('thumb')
                    ->square(),
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('link')
                    ->label('链接')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (PublishStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (PublishStatus $state): string => $state->getLabel()),
                TextColumn::make('start_at')
                    ->label('生效时间')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label('过期时间')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('状态')
                    ->options(PublishStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
