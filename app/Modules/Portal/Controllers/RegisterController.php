<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Models\User;
use App\Modules\Portal\Requests\RegisterRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class RegisterController extends BaseController implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
            new Middleware('throttle:auth', only: ['store']),
        ];
    }

    #[OA\Get(path: '/register', summary: '注册页面', tags: ['模块'])]
    public function show(): Renderable
    {
        return view('portal::register');
    }

    #[OA\Post(path: '/register', summary: '提交注册', tags: ['模块'])]
    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create($request->validated());

        Auth::attempt($request->only(RegisterRequest::getEmail, RegisterRequest::getPassword));
        $request->session()->regenerate();

        return redirect('/');
    }
}
