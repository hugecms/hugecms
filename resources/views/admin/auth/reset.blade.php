<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>重置密码 - {{ config('app.name', 'HugeCMS') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/zui/zui.css') }}">
    <style>
        body { background: #2b3548; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-panel { width: 360px; }
        .login-brand { color: #fff; text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-panel">
        <div class="login-brand">{{ config('app.name', 'HugeCMS') }}</div>
        <div class="panel">
            <div class="panel-heading"><strong>重置密码</strong></div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.password.reset.post') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $email ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">新密码</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">确认新密码</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-block">{{ $errors->first() }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary btn-block">重置密码</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
