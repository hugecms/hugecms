@extends('layouts.app')

@section('title', '会员中心 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    <div class="antd-card p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#1677FF] text-white font-bold flex items-center justify-center text-lg shadow-sm">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-base font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-xs text-slate-500">{{ $user->email }}</p>
            </div>
        </div>

        <span class="antd-tag">
            企业平台用户
        </span>
    </div>

    @if (session('status'))
        <div class="p-3 rounded bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 text-emerald-600 dark:text-emerald-400 text-xs">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="antd-card p-6 space-y-4">
            <h2 class="text-xs font-bold text-slate-900 dark:text-white border-b border-[#f0f0f0] dark:border-[#303030] pb-2">
                更新资料
            </h2>

            <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">用户名</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">邮箱地址</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
                </div>

                <button type="submit" class="antd-btn-primary px-4 py-1.5 text-xs shadow-sm">
                    保存资料
                </button>
            </form>
        </div>

        <div class="antd-card p-6 space-y-4">
            <h2 class="text-xs font-bold text-slate-900 dark:text-white border-b border-[#f0f0f0] dark:border-[#303030] pb-2">
                修改密码
            </h2>

            <form method="POST" action="{{ route('member.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">当前密码</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">新密码</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">确认新密码</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#141414] text-slate-900 dark:text-white text-xs focus:outline-none focus:border-[#4096FF]">
                </div>

                <button type="submit" class="antd-btn-default px-4 py-1.5 text-xs">
                    更新密码
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
