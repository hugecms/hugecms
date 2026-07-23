@extends('layouts.app')

@section('title', '找回密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bento-card rounded-3xl p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">重置密码</h1>
            <p class="text-xs text-slate-500">输入您的注册邮箱接收密码重置链接</p>
        </div>

        @if (session('status'))
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 text-emerald-600 dark:text-emerald-400 text-xs">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">注册邮箱</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/20 transition-all">
                发送重置邮件
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100 dark:border-slate-800">
            记起密码了？
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">返回登录</a>
        </div>
    </div>
</div>
@endsection
