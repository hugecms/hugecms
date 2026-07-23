@extends('layouts.app')

@section('title', '新用户注册 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="card-craft rounded-2xl p-8 space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-serif font-black text-zinc-900 dark:text-white">创建新账号</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">加入我们的读者与创作者社区</p>
        </div>

        @if ($errors->any())
            <div class="p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">用户名 / 昵称</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">设置密码</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">确认密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-sm focus:outline-none focus:border-zinc-400">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity">
                完成注册
            </button>
        </form>

        <div class="text-center text-xs text-zinc-500 dark:text-zinc-400 pt-2 border-t border-zinc-100 dark:border-zinc-800">
            已有账号？
            <a href="{{ route('login') }}" class="text-zinc-900 dark:text-white font-semibold hover:underline">立即登录</a>
        </div>
    </div>
</div>
@endsection
