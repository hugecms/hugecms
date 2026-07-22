<?php

namespace App\Filament\Admin\Resources\Links\Schemas;

use App\Enums\PublishStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('name')
                            ->label('名称')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('link')
                            ->label('链接')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->integer()
                            ->default(0)
                            ->required(),
                        Select::make('status')
                            ->label('状态')
                            ->options(PublishStatus::class)
                            ->default(PublishStatus::Draft->value)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
