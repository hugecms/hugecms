<?php

namespace App\Filament\Admin\Resources\Menus\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('name')
                            ->label('菜单名称')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('标识')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('描述')
                            ->rows(2)
                            ->maxLength(65535),
                    ])
                    ->columns(2),
            ]);
    }
}
