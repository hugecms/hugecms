<?php

namespace App\Filament\Admin\Resources\DailyStatistics;

use App\Filament\Admin\Resources\DailyStatistics\Pages\ListDailyStatistics;
use App\Filament\Admin\Resources\DailyStatistics\Tables\DailyStatisticsTable;
use App\Models\DailyStatistic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DailyStatisticResource extends Resource
{
    protected static ?string $model = DailyStatistic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartPie;

    protected static string|UnitEnum|null $navigationGroup = '数据报表';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = '访问报表';

    protected static ?string $modelLabel = '访问统计';

    protected static ?string $pluralModelLabel = '访问统计';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return DailyStatisticsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyStatistics::route('/'),
        ];
    }
}
