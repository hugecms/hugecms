@extends('layouts.app')

@section('title', '找回密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form">
    <div class="auth-card-bento">
        <div class="auth-header">
            <h1 class="auth-title-bento">重置密码</h1>
            <p class="auth-subtitle">输入您的注册邮箱接收密码重置链接</p>
        </div>

        @if (session('status'))
            <div class="alert-success alert-bento">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error alert-bento">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div>
                <label for="email" class="form-label-bento">注册邮箱</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="form-input-bento">
            </div>

            <button type="submit" class="btn-bento">
                发送重置邮件
            </button>
        </form>

        <div class="auth-footer-bento">
            记起密码了？
            <a href="{{ route('login') }}" class="auth-link-bento">返回登录</a>
        </div>
    </div>
</div>
@endsection
