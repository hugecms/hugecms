<?php

namespace App\Filament\Admin\Widgets;

use App\Services\StatisticsService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PopularArticlesWidget extends TableWidget
{
    protected static ?string $heading = '热门文章（近 7 天）';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $statistics = app(StatisticsService::class);

                return $statistics->popularArticles(
                    limit: 10,
                    start: now()->subDays(6)->startOfDay(),
                    end: now()->endOfDay()
                );
            })
            ->columns([
                TextColumn::make('title')
                    ->label('文章标题')
                    ->wrap(),
                TextColumn::make('views_count')
                    ->label('浏览量')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('发布时间')
                    ->dateTime(),
            ])
            ->defaultSort('views_count', 'desc');
    }
}
