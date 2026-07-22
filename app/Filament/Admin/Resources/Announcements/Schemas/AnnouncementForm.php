<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use App\Enums\PublishStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('title')
                            ->label('标题')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('内容')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('type')
                            ->label('类型')
                            ->options([
                                'info' => '信息',
                                'warning' => '警告',
                                'success' => '成功',
                                'danger' => '危险',
                            ])
                            ->default('info')
                            ->required(),
                    ]),

                Section::make('发布设置')
                    ->schema([
                        Select::make('status')
                            ->label('状态')
                            ->options(PublishStatus::class)
                            ->default(PublishStatus::Draft->value)
                            ->required(),
                        DateTimePicker::make('start_at')
                            ->label('生效时间')
                            ->nullable(),
                        DateTimePicker::make('end_at')
                            ->label('过期时间')
                            ->nullable()
                            ->after('start_at'),
                    ])
                    ->columns(3),
            ]);
    }
}
