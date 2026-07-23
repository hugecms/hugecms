<?php

namespace App\Filament\Admin\Resources\Comments\Tables;

use App\Enums\CommentStatus;
use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content')
                    ->label('内容')
                    ->searchable()
                    ->limit(80),
                TextColumn::make('target')
                    ->label('归属')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(fn (Builder $query) => $query
                            ->whereHas('article', fn (Builder $q) => $q->where('title', 'like', "%{$search}%"))
                            ->orWhereHas('page', fn (Builder $q) => $q->where('title', 'like', "%{$search}%"))
                        );
                    }),
                TextColumn::make('author')
                    ->label('作者')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(fn (Builder $query) => $query
                            ->whereHas('user', fn (Builder $q) => $q->where('name', 'like', "%{$search}%"))
                            ->orWhere('guest_name', 'like', "%{$search}%")
                        );
                    }),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (CommentStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (CommentStatus $state): string => $state->getLabel()),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('状态')
                    ->options(CommentStatus::class),
                SelectFilter::make('article')
                    ->label('文章')
                    ->relationship('article', 'title'),
                SelectFilter::make('page')
                    ->label('页面')
                    ->relationship('page', 'title'),
                SelectFilter::make('user')
                    ->label('用户')
                    ->relationship('user', 'name'),
                Filter::make('author')
                    ->label('作者')
                    ->form([
                        TextInput::make('author')->label('作者关键词'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['author'] ?? null,
                            fn (Builder $query, string $author): Builder => $query->where(fn (Builder $query) => $query
                                ->whereHas('user', fn (Builder $q) => $q->where('name', 'like', "%{$author}%"))
                                ->orWhere('guest_name', 'like', "%{$author}%")
                            )
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approve')
                    ->label('通过')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn (Comment $record): bool => $record->status !== CommentStatus::Approved)
                    ->action(fn (Comment $record) => $record->update(['status' => CommentStatus::Approved])),
                Action::make('reject')
                    ->label('拒绝')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXMark)
                    ->visible(fn (Comment $record): bool => $record->status !== CommentStatus::Rejected)
                    ->action(fn (Comment $record) => $record->update(['status' => CommentStatus::Rejected])),
                Action::make('reply')
                    ->label('回复')
                    ->icon(Heroicon::OutlinedArrowUturnLeft)
                    ->form([
                        Textarea::make('content')
                            ->label('回复内容')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (Comment $record, array $data): void {
                        Comment::create([
                            'content' => $data['content'],
                            'status' => CommentStatus::Approved,
                            'article_id' => $record->article_id,
                            'page_id' => $record->page_id,
                            'user_id' => auth()->id(),
                            'parent_id' => $record->id,
                        ]);
                    }),
                Action::make('markAsDeleted')
                    ->label('删除')
                    ->color('gray')
                    ->icon(Heroicon::OutlinedTrash)
                    ->requiresConfirmation()
                    ->action(fn (Comment $record) => $record->update(['status' => CommentStatus::Deleted])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->label('批量通过')
                        ->icon(Heroicon::OutlinedCheck)
                        ->action(fn (Collection $records) => $records->each->update(['status' => CommentStatus::Approved])),
                    BulkAction::make('reject')
                        ->label('批量拒绝')
                        ->icon(Heroicon::OutlinedXMark)
                        ->action(fn (Collection $records) => $records->each->update(['status' => CommentStatus::Rejected])),
                    BulkAction::make('markAsDeleted')
                        ->label('批量删除')
                        ->icon(Heroicon::OutlinedTrash)
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => CommentStatus::Deleted])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
