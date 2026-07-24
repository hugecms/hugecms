@extends('layouts.app')

@section('title', '重置密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form">
    <div class="auth-card-bento">
        <div class="auth-header">
            <h1 class="auth-title-bento">设置新密码</h1>
            <p class="auth-subtitle">请输入您的新密码</p>
        </div>

        @if ($errors->any())
            <div class="alert-error alert-bento">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="form-label-bento">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email', request()->email) }}" required autofocus
                       class="form-input-bento">
            </div>

            <div>
                <label for="password" class="form-label-bento">新密码</label>
                <input type="password" name="password" id="password" required
                       class="form-input-bento">
            </div>

            <div>
                <label for="password_confirmation" class="form-label-bento">确认新密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="form-input-bento">
            </div>

            <button type="submit" class="btn-bento">
                更新密码
            </button>
        </form>
    </div>
</div>
@endsection
