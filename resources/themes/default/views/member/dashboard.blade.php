@extends('layouts.app')

@section('title', '会员中心 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-narrow dashboard-page">

    {{-- Header Profile Card --}}
    <div class="card profile-card">
        <div class="profile-identity">
            <div class="avatar avatar-xl avatar-invert">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
            </div>
        </div>

        <div>
            <span class="badge-outline">
                注册会员
            </span>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Forms Grid --}}
    <div class="form-grid">

        {{-- Profile Update Form --}}
        <div class="card form-card">
            <h2 class="form-card-title">
                更新资料
            </h2>

            <form method="POST" action="{{ route('member.profile.update') }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label for="name" class="form-label">用户名</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="form-input form-input-sm">
                </div>

                <div class="form-field">
                    <label for="email" class="form-label">邮箱地址</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="form-input form-input-sm">
                </div>

                <button type="submit" class="btn-primary">
                    保存个人资料
                </button>
            </form>
        </div>

        {{-- Password Update Form --}}
        <div class="card form-card">
            <h2 class="form-card-title">
                修改密码
            </h2>

            <form method="POST" action="{{ route('member.password.update') }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label for="current_password" class="form-label">当前密码</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="form-input form-input-sm">
                </div>

                <div class="form-field">
                    <label for="password" class="form-label">新密码</label>
                    <input type="password" name="password" id="password" required
                           class="form-input form-input-sm">
                </div>

                <div class="form-field">
                    <label for="password_confirmation" class="form-label">确认新密码</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="form-input form-input-sm">
                </div>

                <button type="submit" class="btn-secondary">
                    更新密码
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
