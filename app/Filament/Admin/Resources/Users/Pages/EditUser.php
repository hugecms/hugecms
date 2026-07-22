<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (User $record): bool => $record->isNot(auth()->user())),
        ];
    }

    /**
     * 服务端兜底：编辑自己时强制保留原状态，防止禁用自己（表单禁用仅是 UI 层）。
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->is(auth()->user())) {
            $data['status'] = $this->record->status;
        }

        return $data;
    }
}
