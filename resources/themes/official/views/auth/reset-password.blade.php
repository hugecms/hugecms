@extends('layouts.app')

@section('title', '重置密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bento-card rounded-3xl p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">设置新密码</h1>
            <p class="text-xs text-slate-500">请输入您的新密码</p>
        </div>

        @if ($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email', request()->email) }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">新密码</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">确认新密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/20 transition-all">
                更新密码
            </button>
        </form>
    </div>
</div>
@endsection
