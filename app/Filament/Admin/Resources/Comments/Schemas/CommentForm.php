<?php

namespace App\Filament\Admin\Resources\Comments\Schemas;

use App\Enums\CommentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        Select::make('article_id')
                            ->label('所属文章')
                            ->relationship('article', 'title')
                            ->nullable()
                            ->preload()
                            ->searchable(),
                        Select::make('page_id')
                            ->label('所属页面')
                            ->relationship('page', 'title')
                            ->nullable()
                            ->preload()
                            ->searchable()
                            ->helperText('文章和页面都留空表示留言板留言'),
                        Select::make('status')
                            ->label('状态')
                            ->options(CommentStatus::class)
                            ->default(CommentStatus::Approved->value)
                            ->required(),
                        Textarea::make('content')
                            ->label('内容')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('作者信息')
                    ->schema([
                        Select::make('user_id')
                            ->label('注册用户')
                            ->relationship('user', 'name')
                            ->nullable()
                            ->preload()
                            ->searchable(),
                        TextInput::make('guest_name')
                            ->label('访客昵称')
                            ->nullable(),
                        TextInput::make('guest_email')
                            ->label('访客邮箱')
                            ->email()
                            ->nullable(),
                    ]),

                Section::make('回复关系')
                    ->schema([
                        Select::make('parent_id')
                            ->label('回复目标')
                            ->relationship('parent', 'content')
                            ->nullable()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }
}
