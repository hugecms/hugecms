<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录 - {{ config('app.name', 'HugeCMS') }}</title>
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
            <div class="panel-heading"><strong>后台登录</strong></div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.auth.login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="text" id="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="remember" value="1"> 记住我
                        </label>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-block">{{ $errors->first() }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary btn-block">登 录</button>
                    <div class="text-center" style="margin-top:10px">
                        <a href="{{ route('admin.password.forgot') }}">忘记密码？</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
