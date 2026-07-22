<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('角色标识')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions_count')
                    ->label('权限数')
                    ->counts('permissions')
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label('用户数')
                    ->counts('users')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Role $record): bool => $record->name !== 'super_admin'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
