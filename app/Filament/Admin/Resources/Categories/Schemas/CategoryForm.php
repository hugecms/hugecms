<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
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
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL 标识'),
                        Textarea::make('description')
                            ->label('描述')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('父分类')
                            ->relationship('parent', 'name', fn ($query) => $query->whereNot('id', request()->route('record')?->id))
                            ->nullable()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO 标题'),
                        TextInput::make('seo_description')
                            ->label('SEO 描述'),
                        TextInput::make('seo_keywords')
                            ->label('SEO 关键词'),
                    ]),
            ]);
    }
}
