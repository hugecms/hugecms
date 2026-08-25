<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public const string getEmail = 'email';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['required', 'email'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
        ];
    }
}
