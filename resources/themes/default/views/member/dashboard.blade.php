@extends('layouts.app')

@section('title', '会员中心 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    
    {{-- Header Profile Card --}}
    <div class="card-craft rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-14 h-14 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-serif font-black flex items-center justify-center text-xl">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-lg font-serif font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-mono bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800">
                注册会员
            </span>
        </div>
    </div>

    @if (session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 text-emerald-600 dark:text-emerald-400 text-xs">
            {{ session('status') }}
        </div>
    @endif

    {{-- Forms Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- Profile Update Form --}}
        <div class="card-craft rounded-2xl p-6 space-y-4">
            <h2 class="text-sm font-serif font-bold text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-3">
                更新资料
            </h2>

            <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">用户名</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-xs focus:outline-none focus:border-zinc-400">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">邮箱地址</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-xs focus:outline-none focus:border-zinc-400">
                </div>

                <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity">
                    保存个人资料
                </button>
            </form>
        </div>

        {{-- Password Update Form --}}
        <div class="card-craft rounded-2xl p-6 space-y-4">
            <h2 class="text-sm font-serif font-bold text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-3">
                修改密码
            </h2>

            <form method="POST" action="{{ route('member.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">当前密码</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-3.5 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-xs focus:outline-none focus:border-zinc-400">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">新密码</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3.5 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-xs focus:outline-none focus:border-zinc-400">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">确认新密码</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-3.5 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white text-xs focus:outline-none focus:border-zinc-400">
                </div>

                <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-800 text-white font-bold text-xs hover:bg-zinc-700 transition-colors">
                    更新密码
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
