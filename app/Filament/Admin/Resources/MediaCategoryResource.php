<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaCategoryResource\Pages\CreateMediaCategory;
use App\Filament\Admin\Resources\MediaCategoryResource\Pages\EditMediaCategory;
use App\Filament\Admin\Resources\MediaCategoryResource\Pages\ListMediaCategories;
use App\Models\MediaCategory;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MediaCategoryResource extends Resource
{
    protected static ?string $model = MediaCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = '媒体资源';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = '媒体分类';

    protected static ?string $modelLabel = '媒体分类';

    protected static ?string $pluralModelLabel = '媒体分类';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('名称')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->unique(ignoreRecord: true)
                    ->helperText('URL 标识，留空则自动从名称生成'),
                Textarea::make('description')
                    ->label('描述')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('排序')
                    ->integer()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('名称')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                TextColumn::make('media_count')
                    ->label('媒体数')
                    ->counts('media')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaCategories::route('/'),
            'create' => CreateMediaCategory::route('/create'),
            'edit' => EditMediaCategory::route('/{record}/edit'),
        ];
    }
}
