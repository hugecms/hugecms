<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Modules\Portal\Requests\ForgotPasswordRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

class PasswordResetController extends BaseController implements HasMiddleware
{
    /**
     * 密码重置流程各状态的中文提示
     *
     * @var array<string, string>
     */
    private const array STATUS_MESSAGES = [
        Password::RESET_LINK_SENT => '重置链接已发送，请查收邮件',
        Password::RESET_THROTTLED => '请求过于频繁，请稍后再试',
        Password::INVALID_USER => '未找到该邮箱对应的用户',
        Password::INVALID_TOKEN => '重置链接无效或已过期',
        Password::PASSWORD_RESET => '密码重置成功，请使用新密码登录',
    ];

    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
            new Middleware('throttle:auth', only: ['email', 'update']),
        ];
    }

    #[OA\Get(path: '/forgot-password', summary: '忘记密码页面', tags: ['模块'])]
    public function request(): Renderable
    {
        return view('portal::forgot-password');
    }

    #[OA\Post(path: '/forgot-password', summary: '发送密码重置邮件', tags: ['模块'])]
    public function email(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->validated());

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withInput($request->only(ForgotPasswordRequest::getEmail))
                ->withErrors(['email' => $this->statusMessage($status)]);
        }

        return back()->with('status', $this->statusMessage($status));
    }

    private function statusMessage(string $status): string
    {
        return self::STATUS_MESSAGES[$status] ?? '操作失败，请重试';
    }
}
