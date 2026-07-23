<?php

namespace App\Filament\Admin\Resources\DailyStatistics\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DailyStatisticsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('日期')
                    ->sortable(),
                TextColumn::make('page_views')
                    ->label('PV')
                    ->sortable(),
                TextColumn::make('unique_visitors')
                    ->label('UV')
                    ->sortable(),
                TextColumn::make('new_users')
                    ->label('新增用户')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start')->label('开始日期'),
                        DatePicker::make('end')->label('结束日期'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start'] ?? null,
                                fn (Builder $query, string $start) => $query->where('date', '>=', $start)
                            )
                            ->when(
                                $data['end'] ?? null,
                                fn (Builder $query, string $end) => $query->where('date', '<=', $end)
                            );
                    }),
            ])
            ->defaultSort('date', 'desc');
    }
}
