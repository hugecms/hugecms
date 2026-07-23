<?php

namespace App\Filament\Admin\Resources\OperationLogs;

use App\Filament\Admin\Resources\OperationLogs\Pages\ListOperationLogs;
use App\Filament\Admin\Resources\OperationLogs\Tables\OperationLogsTable;
use App\Models\OperationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OperationLogResource extends Resource
{
    protected static ?string $model = OperationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = '用户与权限';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = '操作日志';

    protected static ?string $modelLabel = '操作日志';

    protected static ?string $pluralModelLabel = '操作日志';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return OperationLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperationLogs::route('/'),
        ];
    }
}
