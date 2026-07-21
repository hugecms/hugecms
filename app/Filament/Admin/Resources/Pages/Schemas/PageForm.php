<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('内容')
                    ->schema([
                        TextInput::make('title')
                            ->label('标题')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL 标识'),
                        Select::make('status')
                            ->label('状态')
                            ->options([
                                'draft' => '草稿',
                                'published' => '已发布',
                            ])
                            ->default('draft')
                            ->required(),
                        RichEditor::make('content')
                            ->label('正文')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('pages'),
                    ]),

                Section::make('封面图')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('页面封面')
                            ->collection('cover')
                            ->image()
                            ->conversion('medium')
                            ->columnSpanFull(),
                    ]),

                Section::make('层级与作者')
                    ->schema([
                        Select::make('parent_id')
                            ->label('父页面')
                            ->relationship('parent', 'title', fn ($query) => $query->whereNot('id', request()->route('record')?->id))
                            ->nullable()
                            ->searchable()
                            ->preload(),
                        Select::make('user_id')
                            ->label('作者')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),
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
