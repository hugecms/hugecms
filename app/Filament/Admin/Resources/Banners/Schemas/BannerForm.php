<?php

namespace App\Filament\Admin\Resources\Banners\Schemas;

use App\Enums\PublishStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
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
                        SpatieMediaLibraryFileUpload::make('banner_image')
                            ->label('图片')
                            ->collection('banner_image')
                            ->image()
                            ->conversion('medium')
                            ->columnSpanFull(),
                        TextInput::make('link')
                            ->label('链接')
                            ->url()
                            ->nullable(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->integer()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),

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
