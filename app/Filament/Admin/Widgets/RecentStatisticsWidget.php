<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DailyStatistic;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentStatisticsWidget extends TableWidget
{
    protected static ?string $heading = '近 7 天访问趋势';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DailyStatistic::query()
                    ->where('date', '>=', now()->subDays(6)->toDateString())
                    ->orderByDesc('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('日期'),
                TextColumn::make('page_views')
                    ->label('PV'),
                TextColumn::make('unique_visitors')
                    ->label('UV'),
                TextColumn::make('new_users')
                    ->label('新增用户'),
            ]);
    }
}
