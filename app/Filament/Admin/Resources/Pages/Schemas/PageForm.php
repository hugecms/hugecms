<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use App\Enums\ContentStatus;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                                if ($operation === 'create' && blank($get('slug'))) {
                                    $set('slug', Str::slug($state, language: 'zh'));
                                }
                            })
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true)
                            ->helperText('URL 标识，留空则自动从标题生成'),
                        Select::make('status')
                            ->label('状态')
                            ->options(ContentStatus::class)
                            ->default(ContentStatus::Draft->value)
                            ->required(),
                        Select::make('template')
                            ->label('页面模板')
                            ->options([
                                'default' => '默认模板',
                            ])
                            ->default('default')
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
                            ->relationship('parent', 'title', function ($query) {
                                $record = request()->route('record');
                                if (! $record) {
                                    return $query;
                                }

                                return $query
                                    ->whereNot('id', $record->id)
                                    ->whereNotBetween('_lft', [$record->_lft, $record->_rgt]);
                            })
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
                        Textarea::make('seo_description')
                            ->label('SEO 描述')
                            ->rows(2),
                        TextInput::make('seo_keywords')
                            ->label('SEO 关键词'),
                    ]),
            ]);
    }
}
