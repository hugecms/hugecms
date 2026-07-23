@extends('layouts.app')

@section('title', '用户登录 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="card-craft rounded-2xl p-8 space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-serif font-black text-zinc-900 dark:text-white">欢迎回来</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">登录您的账号以继续</p>
        </div>

        @if ($errors->any())
            <div class="p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">密码</label>
                    <a href="{{ route('password.request') }}" class="text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white">忘记密码？</a>
                </div>
                <input type="password" name="password" id="password" required
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="remember" id="remember" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500">
                <label for="remember" class="text-xs text-zinc-600 dark:text-zinc-400">记住登录状态</label>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity">
                登录账号
            </button>
        </form>

        <div class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-2 border-t border-zinc-100 dark:border-zinc-800">
            还没有账号？
            <a href="{{ route('register') }}" class="text-zinc-900 dark:text-white font-semibold hover:underline">立即注册</a>
        </div>
    </div>
</div>
@endsection
