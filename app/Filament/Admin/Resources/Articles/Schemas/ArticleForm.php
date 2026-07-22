<?php

namespace App\Filament\Admin\Resources\Articles\Schemas;

use App\Enums\ContentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                if ($operation === 'create' && blank($get('slug'))) {
                                    $set('slug', Str::slug($state, language: 'zh'));
                                }
                            })
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->helperText('URL 标识，留空则自动从标题生成')
                            ->columnSpan(2),
                        Select::make('status')
                            ->options(ContentStatus::class)
                            ->default(ContentStatus::Draft->value)
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

                Section::make('推荐设置')
                    ->schema([
                        Toggle::make('is_pinned')
                            ->label('置顶')
                            ->columnSpan(1),
                        Toggle::make('is_recommended')
                            ->label('推荐')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

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
                        Textarea::make('seo_description')
                            ->label('SEO 描述')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('seo_keywords')
                            ->label('SEO 关键词')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
