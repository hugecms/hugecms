<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaResource\Pages\EditMedia;
use App\Filament\Admin\Resources\MediaResource\Pages\ListMedia;
use App\Models\Media;
use App\Services\MediaReferenceService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = '媒体资源';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = '媒体库';

    protected static ?string $modelLabel = '媒体';

    protected static ?string $pluralModelLabel = '媒体库';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('显示名称')
                    ->required(),
                Select::make('media_category_id')
                    ->label('分类')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('预览')
                    ->state(fn (BaseMedia $record): string => $record->hasGeneratedConversion('thumb')
                        ? $record->getUrl('thumb')
                        : $record->getUrl())
                    ->square(),
                TextColumn::make('name')
                    ->label('文件名')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('分类')
                    ->badge()
                    ->sortable(),
                TextColumn::make('dimensions')
                    ->label('尺寸')
                    ->state(function (BaseMedia $record): ?string {
                        $width = $record->getCustomProperty('width');
                        $height = $record->getCustomProperty('height');

                        if ($width && $height) {
                            return "{$width} × {$height}";
                        }

                        return null;
                    }),
                TextColumn::make('human_readable_size')
                    ->label('大小')
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('类型')
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('引用位置')
                    ->state(function (BaseMedia $record): string {
                        $service = app(MediaReferenceService::class);
                        $result = $service->check($record);

                        if (! $result['referenced']) {
                            return '未引用';
                        }

                        return $result['references']
                            ->pluck('type')
                            ->unique()
                            ->implode('、');
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === '未引用' ? 'gray' : 'warning'),
                TextColumn::make('created_at')
                    ->label('上传时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('media_category_id')
                    ->label('分类')
                    ->relationship('category', 'name'),
                SelectFilter::make('mime_type')
                    ->label('类型')
                    ->options(function () {
                        return Media::query()
                            ->select('mime_type')
                            ->distinct()
                            ->pluck('mime_type', 'mime_type')
                            ->toArray();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (BaseMedia $record, DeleteAction $action) {
                        $service = app(MediaReferenceService::class);

                        if ($service->isReferenced($record)) {
                            Notification::make()
                                ->danger()
                                ->title('无法删除')
                                ->body('该文件正被内容引用，无法删除。')
                                ->send();

                            $action->halt();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}
