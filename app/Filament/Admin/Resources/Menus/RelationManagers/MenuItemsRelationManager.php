<?php

namespace App\Filament\Admin\Resources\Menus\RelationManagers;

use App\Enums\MenuItemTarget;
use App\Enums\MenuItemType;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = '菜单项';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('标题')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('类型')
                    ->options(MenuItemType::class)
                    ->default(MenuItemType::Custom->value)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('linkable_id', null)),

                Select::make('target')
                    ->label('打开方式')
                    ->options(MenuItemTarget::class)
                    ->default(MenuItemTarget::Self->value)
                    ->required(),

                TextInput::make('url')
                    ->label('链接')
                    ->url()
                    ->maxLength(2048)
                    ->visible(fn (Get $get): bool => $get('type') === MenuItemType::Custom->value),

                Select::make('linkable_id')
                    ->label('关联内容')
                    ->visible(fn (Get $get): bool => $get('type') !== MenuItemType::Custom->value && filled($get('type')))
                    ->options(function (Get $get): array {
                        return match ($get('type')) {
                            MenuItemType::Category->value => Category::pluck('name', 'id')->toArray(),
                            MenuItemType::Page->value => Page::pluck('title', 'id')->toArray(),
                            MenuItemType::Article->value => Article::pluck('title', 'id')->toArray(),
                            default => [],
                        };
                    })
                    ->searchable(),

                Select::make('parent_id')
                    ->label('父级菜单项')
                    ->options(fn (): array => $this->ownerRecord->items()->pluck('title', 'id')->toArray())
                    ->native(false)
                    ->nullable(),

                TextInput::make('order')
                    ->label('排序')
                    ->integer()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('启用')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('类型')
                    ->badge()
                    ->formatStateUsing(fn (MenuItemType $state): string => $state->getLabel()),
                TextColumn::make('target')
                    ->label('打开方式')
                    ->formatStateUsing(fn (MenuItemTarget $state): string => $state->getLabel()),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
                TextColumn::make('order')
                    ->label('排序')
                    ->sortable(),
            ])
            ->defaultSort('_lft')
            ->reorderable('_lft')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
