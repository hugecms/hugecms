<?php

namespace App\Filament\Admin\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('内容')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->helperText('URL 标识，留空则自动从标题生成')
                            ->columnSpan(2),
                        Select::make('status')
                            ->options([
                                'draft' => '草稿',
                                'published' => '已发布',
                            ])
                            ->default('draft')
                            ->required()
                            ->columnSpan(1),
                        Textarea::make('excerpt')
                            ->label('摘要')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('正文')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('articles'),
                        DateTimePicker::make('published_at')
                            ->label('发布时间')
                            ->hint('不填则不会在前台展示'),
                    ])
                    ->columns(3),

                Section::make('分类与标签')
                    ->schema([
                        Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpan(1),
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('slug')->required(),
                                TextInput::make('description'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('封面图')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('封面图片')
                            ->collection('cover')
                            ->image()
                            ->conversion('medium')
                            ->columnSpanFull(),
                    ]),

                Section::make('作者')
                    ->schema([
                        Select::make('user_id')
                            ->label('作者')
                            ->relationship('user', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO 标题')
                            ->columnSpanFull(),
                        TextInput::make('seo_description')
                            ->label('SEO 描述')
                            ->columnSpanFull(),
                        TextInput::make('seo_keywords')
                            ->label('SEO 关键词')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
