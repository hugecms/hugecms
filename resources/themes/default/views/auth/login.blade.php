@extends('layouts.app')

@section('title', '用户登录 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form auth-page">
    <div class="card auth-card">
        <div class="auth-head">
            <h1 class="auth-title">欢迎回来</h1>
            <p class="auth-subtitle">登录您的账号以继续</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            <div class="form-field">
                <label for="email" class="form-label">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="form-input">
            </div>

            <div class="form-field">
                <div class="form-row-between">
                    <label for="password" class="form-label">密码</label>
                    <a href="{{ route('password.request') }}" class="form-link">忘记密码？</a>
                </div>
                <input type="password" name="password" id="password" required
                       class="form-input">
            </div>

            <div class="checkbox-row">
                <input type="checkbox" name="remember" id="remember" class="form-checkbox">
                <label for="remember" class="checkbox-label">记住登录状态</label>
            </div>

            <button type="submit" class="btn-primary btn-block">
                登录账号
            </button>
        </form>

        <div class="auth-foot">
            还没有账号？
            <a href="{{ route('register') }}" class="auth-foot-link">立即注册</a>
        </div>
    </div>
</div>
@endsection
