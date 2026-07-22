<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use App\Support\Permissions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('name')
                            ->label('角色标识')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('英文标识，如 editor、operator'),
                    ]),

                Section::make('权限')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('权限')
                            ->relationship('permissions', 'name')
                            ->options(fn () => Permission::all()
                                ->mapWithKeys(fn (Permission $permission) => [
                                    $permission->id => Permissions::label($permission->name),
                                ])
                                ->sort()
                            )
                            ->columns(3)
                            ->bulkToggleable()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
