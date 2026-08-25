<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Modules\Portal\Requests\LoginRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends BaseController implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    #[OA\Get(path: '/login', summary: '登录页面', tags: ['模块'])]
    public function login(): Renderable
    {
        return view('portal::login');
    }

    #[OA\Post(path: '/login', summary: '提交登录', tags: ['模块'])]
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    #[OA\Post(path: '/logout', summary: '退出登录', tags: ['模块'])]
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
