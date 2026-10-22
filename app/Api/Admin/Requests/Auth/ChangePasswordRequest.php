<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthChangePasswordRequest',
    required: [
        self::getOldPassword,
        self::getNewPassword,
    ],
    properties: [
        new OA\Property(property: self::getOldPassword, description: '原密码', type: 'string'),
        new OA\Property(property: self::getNewPassword, description: '新密码,至少6位', type: 'string'),
        new OA\Property(property: self::getNewPasswordConfirmation, description: '确认新密码', type: 'string'),
    ]
)]
class ChangePasswordRequest extends FormRequest
{
    public const string getOldPassword = 'old_password';

    public const string getNewPassword = 'new_password';

    public const string getNewPasswordConfirmation = 'new_password_confirmation';

    public function rules(): array
    {
        return [
            self::getOldPassword => 'required|string',
            self::getNewPassword => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOldPassword.'.required' => '请填写原密码',
            self::getNewPassword.'.required' => '请填写新密码',
            self::getNewPassword.'.min' => '新密码至少6位',
            self::getNewPassword.'.confirmed' => '两次输入的新密码不一致',
        ];
    }
}
