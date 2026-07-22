<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            ->helperText('URL 标识'),
                        Textarea::make('description')
                            ->label('描述')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('父分类')
                            ->relationship('parent', 'name', function ($query) {
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
                    ]),

                Section::make('封面图')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('分类封面')
                            ->collection('cover')
                            ->image()
                            ->conversion('medium')
                            ->columnSpanFull(),
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
