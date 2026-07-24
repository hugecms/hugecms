@extends('layouts.app')

@section('title', '会员中心 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container-narrow dashboard-page">
    <div class="card profile-card">
        <div class="profile-user">
            <div class="profile-avatar">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
            </div>
        </div>

        <span class="tag">
            企业平台用户
        </span>
    </div>

    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="dashboard-grid">
        <div class="card panel-card">
            <h2 class="panel-title">
                更新资料
            </h2>

            <form method="POST" action="{{ route('member.profile.update') }}" class="auth-form">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="form-label">用户名</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="form-input form-input-sm">
                </div>

                <div>
                    <label for="email" class="form-label">邮箱地址</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="form-input form-input-sm">
                </div>

                <button type="submit" class="btn-primary btn-panel">
                    保存资料
                </button>
            </form>
        </div>

        <div class="card panel-card">
            <h2 class="panel-title">
                修改密码
            </h2>

            <form method="POST" action="{{ route('member.password.update') }}" class="auth-form">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="form-label">当前密码</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="form-input form-input-sm">
                </div>

                <div>
                    <label for="password" class="form-label">新密码</label>
                    <input type="password" name="password" id="password" required
                           class="form-input form-input-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">确认新密码</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="form-input form-input-sm">
                </div>

                <button type="submit" class="btn-default btn-panel">
                    更新密码
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
