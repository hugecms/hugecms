<?php

namespace App\Filament\Admin\Resources\Links;

use App\Filament\Admin\Resources\Links\Pages\CreateLink;
use App\Filament\Admin\Resources\Links\Pages\EditLink;
use App\Filament\Admin\Resources\Links\Pages\ListLinks;
use App\Filament\Admin\Resources\Links\Schemas\LinkForm;
use App\Filament\Admin\Resources\Links\Tables\LinksTable;
use App\Models\Link;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|UnitEnum|null $navigationGroup = '运营工具';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = '友情链接';

    protected static ?string $modelLabel = '友情链接';

    protected static ?string $pluralModelLabel = '友情链接';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }
}
