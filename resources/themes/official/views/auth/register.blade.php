@extends('layouts.app')

@section('title', '新用户注册 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="antd-card p-8 shadow-antd-s2 space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">免费注册 HugeCMS</h1>
            <p class="text-xs text-slate-500">开启极速企业内容管理交付体验</p>
        </div>

        @if ($errors->any())
            <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">用户名 / 昵称</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">设置密码</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">确认密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3 py-2 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
            </div>

            <button type="submit" class="antd-btn-primary w-full py-2 text-xs shadow-sm">
                完成注册并登录
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-[#f0f0f0] dark:border-[#303030]">
            已有账号？
            <a href="{{ route('login') }}" class="text-[#1677FF] font-semibold hover:underline">立即登录</a>
        </div>
    </div>
</div>
@endsection
