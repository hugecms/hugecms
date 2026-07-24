@extends('layouts.app')

@section('title', '找回密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form auth-page">
    <div class="auth-card-alt">
        <div class="auth-head">
            <h1 class="auth-title-alt">找回密码</h1>
            <p class="auth-subtitle-alt">输入您的注册邮箱，系统将发送重置邮件</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div class="form-field">
                <label for="email" class="form-label form-label-slate">注册邮箱</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="form-input-indigo">
            </div>

            <button type="submit" class="btn-indigo btn-indigo-lg btn-block">
                发送重置邮件
            </button>
        </form>

        <div class="auth-foot-alt">
            记起密码了？
            <a href="{{ route('login') }}" class="auth-foot-link-alt">返回登录</a>
        </div>
    </div>
</div>
@endsection
