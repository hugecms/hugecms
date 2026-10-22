<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Auth;

use App\Enums\UserTypeEnum;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthLoginRequest',
    required: [
        self::getUsername,
        self::getPassword,
    ],
    properties: [
        new OA\Property(property: self::getUsername, description: '用户用户名', type: 'string'),
        new OA\Property(property: self::getPassword, description: '用户登录密码', type: 'string'),
        new OA\Property(property: self::getCaptcha, description: '登录图片验证码,V1 未启用', type: 'string', nullable: true),
        new OA\Property(property: self::getCaptchaId, description: '图片验证码ID,V1 未启用', type: 'string', nullable: true),

    ]
)]
class LoginRequest extends FormRequest
{
    const string getUsername = 'user_name';

    const string getPassword = 'password';

    const string getCaptcha = 'captcha';

    const string getCaptchaId = 'captchaId';

    public function rules(): array
    {
        return [
            self::getUsername => ['required'],
            self::getPassword => 'required',
            self::getCaptcha => 'nullable|string',
            self::getCaptchaId => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUsername.'.required' => '请填写用户名',
            self::getPassword.'.required' => '请填写登录密码',
            self::getCaptcha.'.required' => '请填写图片验证码',
            self::getCaptchaId.'.required' => '请填写图片验证码ID',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // API 字段为 user_name，表列为 username——attempt 前映射为真实列名
        $credentials = [
            'username' => (string) $this->input(self::getUsername),
            'password' => (string) $this->input(self::getPassword),
        ];

        // admin_login 为 session 驱动，提供 attempt() 凭据校验；attempt 后同请求内 user() 可取到
        if (! Auth::guard('admin_login')->attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                self::getUsername => '用户名或密码错误',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            self::getUsername => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower(UserTypeEnum::AdminUser->value.'|'.$this->string(self::getUsername)).'|'.$this->ip());
    }
}
