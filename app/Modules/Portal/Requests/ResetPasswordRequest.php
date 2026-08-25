<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public const string getToken = 'token';

    public const string getEmail = 'email';

    public const string getPassword = 'password';

    public const string getPasswordConfirmation = 'password_confirmation';

    /**
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            self::getToken => ['required', 'string'],
            self::getEmail => ['required', 'email'],
            self::getPassword => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getToken.'.required' => '重置令牌缺失',
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getPassword.'.required' => '请输入密码',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
        ];
    }
}
