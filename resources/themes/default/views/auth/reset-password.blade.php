@extends('layouts.app')

@section('title', '重置密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-form auth-page">
    <div class="auth-card-alt">
        <div class="auth-head">
            <h1 class="auth-title-alt">设置新密码</h1>
            <p class="auth-subtitle-alt">请输入您的新密码并确认</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-field">
                <label for="email" class="form-label form-label-slate">邮箱地址</label>
                <input type="email" name="email" id="email" value="{{ old('email', request()->email) }}" required autofocus
                       class="form-input-indigo">
            </div>

            <div class="form-field">
                <label for="password" class="form-label form-label-slate">新密码</label>
                <input type="password" name="password" id="password" required
                       class="form-input-indigo">
            </div>

            <div class="form-field">
                <label for="password_confirmation" class="form-label form-label-slate">确认新密码</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="form-input-indigo">
            </div>

            <button type="submit" class="btn-indigo btn-indigo-lg btn-block">
                更新密码
            </button>
        </form>
    </div>
</div>
@endsection
