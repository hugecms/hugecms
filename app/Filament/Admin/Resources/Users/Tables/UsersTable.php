<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Enums\UserStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('头像')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('name')
                    ->label('用户名')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('手机号')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('roles.name')
                    ->label('角色')
                    ->badge()
                    ->separator(','),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (UserStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (UserStatus $state): string => $state->getLabel()),
                TextColumn::make('created_at')
                    ->label('注册时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('角色')
                    ->relationship('roles', 'name'),
                SelectFilter::make('status')
                    ->label('状态')
                    ->options(UserStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('toggleStatus')
                    ->label(fn (User $record): string => $record->isActive() ? '禁用' : '启用')
                    ->icon(fn (User $record): Heroicon => $record->isActive() ? Heroicon::OutlinedNoSymbol : Heroicon::OutlinedCheck)
                    ->color(fn (User $record): string => $record->isActive() ? 'danger' : 'success')
                    ->authorize('update')
                    ->visible(fn (User $record): bool => $record->isNot(auth()->user()))
                    ->requiresConfirmation()
                    ->action(fn (User $record) => $record->update([
                        'status' => $record->isActive() ? UserStatus::Disabled : UserStatus::Active,
                    ])),
                DeleteAction::make()
                    ->visible(fn (User $record): bool => $record->isNot(auth()->user())),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
