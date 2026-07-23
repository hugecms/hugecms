<?php

namespace App\Filament\Admin\Resources\OperationLogs\Tables;

use App\Enums\OperationLogType;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OperationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('操作类型')
                    ->badge()
                    ->color(fn (OperationLogType $state): string => $state->getColor())
                    ->formatStateUsing(fn (OperationLogType $state): string => $state->getLabel()),
                TextColumn::make('user.name')
                    ->label('操作人'),
                TextColumn::make('object')
                    ->label('对象')
                    ->getStateUsing(fn ($record): string => $record->object_type
                        ? sprintf('%s #%s', $record->object_type, $record->object_id ?? '-')
                        : '-')
                    ->sortable(['object_type', 'object_id']),
                TextColumn::make('ip_address')
                    ->label('IP 地址'),
                TextColumn::make('summary')
                    ->label('变更摘要')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('created_at')
                    ->label('操作时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('操作类型')
                    ->options(OperationLogType::class),
                SelectFilter::make('user')
                    ->label('操作人')
                    ->relationship('user', 'name'),
                Filter::make('created_at')
                    ->label('操作时间')
                    ->form([
                        DatePicker::make('from')->label('开始日期'),
                        DatePicker::make('until')->label('结束日期'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['until'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date),
                        )),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
