<?php

namespace App\Filament\Admin\Resources\Announcements\Tables;

use App\Enums\PublishStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('类型')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'info' => '信息',
                        'warning' => '警告',
                        'success' => '成功',
                        'danger' => '危险',
                        default => $state,
                    })
                    ->color(fn (string $state): string => $state),
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
                SelectFilter::make('type')
                    ->label('类型')
                    ->options([
                        'info' => '信息',
                        'warning' => '警告',
                        'success' => '成功',
                        'danger' => '危险',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }
}
