@extends('layouts.app')

@section('title', '新用户注册 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form auth-page">
    <div class="card auth-card">
        <div class="auth-head">
            <h1 class="auth-title">创建新账号</h1>
            <p class="auth-subtitle">加入我们的读者与创作者社区</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div class="form-field">
                <label for="name" class="form-label">用户名 / 昵称</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="form-input">
            </div>

            <div class="form-field">
                <label for="email" class="form-label">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="form-input">
            </div>

            <div class="form-field">
                <label for="password" class="form-label">设置密码</label>
                <input type="password" name="password" id="password" required
                       class="form-input">
            </div>

            <div class="form-field">
                <label for="password_confirmation" class="form-label">确认密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="form-input">
            </div>

            <button type="submit" class="btn-primary btn-block">
                完成注册
            </button>
        </form>

        <div class="auth-foot">
            已有账号？
            <a href="{{ route('login') }}" class="auth-foot-link">立即登录</a>
        </div>
    </div>
</div>
@endsection
