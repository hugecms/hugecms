<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public const string getEmail = 'email';

    public const string getPassword = 'password';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['required', 'email'],
            self::getPassword => ['required', 'string'],
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
            self::getPassword.'.required' => '请输入密码',
        ];
    }

    /**
     * 尝试登录（含限流保护，失败抛 ValidationException）
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(self::getEmail, self::getPassword), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                self::getEmail => '邮箱或密码错误',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * 确保未触发登录限流
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            self::getEmail => "尝试次数过多，请 {$seconds} 秒后重试",
        ]);
    }

    /**
     * 限流键：邮箱|IP
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string(self::getEmail)).'|'.$this->ip());
    }
}
