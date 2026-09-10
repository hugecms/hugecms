<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * 后台认证：登录、登出、找回密码、重置密码。
 * 页面仅渲染 Blade；认证逻辑接入后替换 TODO 段。
 */
class AuthController extends Controller
{
    /**
     * 登录页。
     */
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * 处理登录（邮箱 + 密码，匹配 users 表）。
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $remember = $request->boolean('remember');

        // TODO: 接入认证（guard web / users 表），并校验 status=1
        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => '邮箱或密码错误'])->onlyInput('email');
    }

    /**
     * 登出。
     */
    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.auth.login');
    }

    /**
     * 找回密码页。
     */
    public function showForgot(): View
    {
        return view('admin.auth.forgot');
    }

    /**
     * 发送密码重置链接（password_reset_tokens 表 + 邮件通道）。
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // TODO: Password::sendResetLink()，邮件通道见 options.smtp_config
        return back()->with('status', '如果该邮箱存在，重置链接已发送，请查收。');
    }

    /**
     * 重置密码页（邮件链接落地）。
     */
    public function showReset(Request $request, string $token): View
    {
        return view('admin.auth.reset', ['token' => $token, 'email' => $request->query('email')]);
    }

    /**
     * 处理重置密码。
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // TODO: Password::reset()
        return redirect()->route('admin.auth.login')->with('status', '密码已重置，请使用新密码登录。');
    }
}
