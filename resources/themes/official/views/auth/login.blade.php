@extends('layouts.app')

@section('title', '控制台登录 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="antd-card p-8 shadow-antd-s2 space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">登录 HugeCMS 控制台</h1>
            <p class="text-xs text-slate-500">输入您的账号密码以继续</p>
        </div>

        @if ($errors->any())
            <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300">密码</label>
                    <a href="{{ route('password.request') }}" class="text-xs text-[#1677FF] hover:underline">忘记密码？</a>
                </div>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-[#1677FF] focus:ring-[#1677FF]">
                <label for="remember" class="text-xs text-slate-600 dark:text-slate-400">记住登录状态</label>
            </div>

            <button type="submit" class="antd-btn-primary w-full py-2 text-xs shadow-sm">
                安全登录
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-[#f0f0f0] dark:border-[#303030]">
            还没有账号？
            <a href="{{ route('register') }}" class="text-[#1677FF] font-semibold hover:underline">免费注册试用</a>
        </div>
    </div>
</div>
@endsection
