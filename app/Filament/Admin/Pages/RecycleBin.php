<?php

namespace App\Filament\Admin\Pages;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\Page as PageModel;
use BackedEnum;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class RecycleBin extends Page implements HasTable
{
    use InteractsWithTable;

    /**
     * 回收站内容类型:{key} => 配置(标签 / 查看权限 / 模型)
     */
    public const TABS = [
        'article' => ['label' => '文章', 'permission' => 'article.view_any', 'model' => Article::class],
        'page' => ['label' => '页面', 'permission' => 'page.view_any', 'model' => PageModel::class],
    ];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrash;

    protected static string|UnitEnum|null $navigationGroup = '内容管理';

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationLabel = '回收站';

    protected static ?string $title = '回收站';

    protected string $view = 'filament.admin.pages.recycle-bin';

    public string $activeTab = 'article';

    public static function canAccess(): bool
    {
        return collect(array_keys(self::TABS))->contains(fn (string $tab): bool => static::canViewTab($tab));
    }

    public static function canViewTab(string $tab): bool
    {
        $permission = self::TABS[$tab]['permission'] ?? null;

        return $permission !== null && auth()->user()->can($permission);
    }

    public function mount(): void
    {
        // 当前 Tab 不可见时落到用户可见的第一个 Tab
        if (! static::canViewTab($this->activeTab)) {
            $this->activeTab = collect(array_keys(self::TABS))
                ->first(fn (string $tab): bool => static::canViewTab($tab)) ?? $this->activeTab;
        }
    }

    public function setTab(string $tab): void
    {
        // Livewire 公共方法可被任意调用，切换前必须校验该类型的查看权限
        if (! static::canViewTab($tab)) {
            return;
        }

        $this->activeTab = $tab;
        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        return self::TABS[$this->activeTab]['model']::onlyTrashed();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns([
                TextColumn::make('title')
                    ->label('标题')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (ContentStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (ContentStatus $state): string => $state->getLabel()),
                TextColumn::make('user.name')
                    ->label('作者'),
                TextColumn::make('deleted_at')
                    ->label('删除时间')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('deleted_at')
                    ->label('删除时间')
                    ->form([
                        DatePicker::make('from')->label('开始日期'),
                        DatePicker::make('until')->label('结束日期'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('deleted_at', '>=', $date),
                        )
                        ->when(
                            $data['until'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('deleted_at', '<=', $date),
                        )),
            ])
            ->recordActions([
                RestoreAction::make()
                    ->authorize('restore'),
                ForceDeleteAction::make()
                    ->authorize('forceDelete'),
            ])
            ->toolbarActions([
                RestoreBulkAction::make()
                    ->authorize('restoreAny'),
                ForceDeleteBulkAction::make()
                    ->authorize('forceDeleteAny'),
            ])
            ->emptyStateHeading('回收站是空的')
            ->defaultSort('deleted_at', 'desc');
    }
}
