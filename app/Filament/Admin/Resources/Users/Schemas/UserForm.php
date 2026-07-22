<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Enums\UserStatus;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('name')
                            ->label('用户名')
                            ->required(),
                        TextInput::make('email')
                            ->label('邮箱')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('手机号')
                            ->tel()
                            ->nullable(),
                        Select::make('status')
                            ->label('状态')
                            ->options(UserStatus::class)
                            ->default(UserStatus::Active)
                            // 禁止管理员在编辑自己时禁用自己
                            ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('密码')
                    ->schema([
                        TextInput::make('password')
                            ->label('密码')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('编辑时留空则不修改密码')
                            ->minLength(8),
                    ]),

                Section::make('角色')
                    ->schema([
                        Select::make('roles')
                            ->label('角色')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),

                Section::make('头像')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label('头像')
                            ->collection('avatar')
                            ->image()
                            ->avatar()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
