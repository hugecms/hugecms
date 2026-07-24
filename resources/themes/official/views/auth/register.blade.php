@extends('layouts.app')

@section('title', '新用户注册 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form">
    <div class="card auth-card shadow-s2">
        <div class="auth-header">
            <h1 class="auth-title">免费注册 HugeCMS</h1>
            <p class="auth-subtitle">开启极速企业内容管理交付体验</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div>
                <label for="name" class="form-label">用户名 / 昵称</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="form-input">
            </div>

            <div>
                <label for="email" class="form-label">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="form-input">
            </div>

            <div>
                <label for="password" class="form-label">设置密码</label>
                <input type="password" name="password" id="password" required
                       class="form-input">
            </div>

            <div>
                <label for="password_confirmation" class="form-label">确认密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="form-input">
            </div>

            <button type="submit" class="btn-primary btn-block">
                完成注册并登录
            </button>
        </form>

        <div class="auth-footer">
            已有账号？
            <a href="{{ route('login') }}" class="auth-link">立即登录</a>
        </div>
    </div>
</div>
@endsection
