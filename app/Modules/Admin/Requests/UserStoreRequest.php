<?php

declare(strict_types=1);

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getPassword = 'password';

    public const string getStatus = 'status';

    /**
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:50'],
            self::getEmail => ['required', 'email', 'max:255', 'unique:users,email'],
            self::getPassword => ['required', 'string', 'confirmed', Password::defaults()],
            self::getStatus => ['required', 'integer', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getName.'.required' => '请输入用户名',
            self::getName.'.max' => '用户名不能超过 50 个字符',
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getEmail.'.unique' => '该邮箱已被注册',
            self::getPassword.'.required' => '请输入密码',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
            self::getStatus.'.required' => '请选择状态',
        ];
    }
}
