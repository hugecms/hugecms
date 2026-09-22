<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录 - {{ config('app.name', 'HugeCMS') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        body { margin: 0; }
        .login-wrap { display: flex; min-height: 100vh; }

        /* 左侧品牌区（Arco Pro 分屏：渐变 + 装饰圆） */
        .login-brand-side {
            flex: 1.2;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-6), var(--primary-7));
        }
        .login-brand-side .logo-mark {
            display: inline-flex; align-items: center; justify-content: center;
            width: 56px; height: 56px; border-radius: var(--border-radius-large);
            background: rgba(255, 255, 255, .18); font-size: 26px; font-weight: 600;
        }
        .login-brand-side h1 { font-size: 32px; font-weight: 600; margin: 24px 0 12px; }
        .login-brand-side p { font-size: 15px; color: rgba(255, 255, 255, .75); max-width: 420px; line-height: 1.8; margin: 0; }
        .deco { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .06); }
        .deco.c1 { width: 420px; height: 420px; right: -160px; top: -140px; }
        .deco.c2 { width: 260px; height: 260px; left: -100px; bottom: -90px; }
        .deco.c3 { width: 120px; height: 120px; right: 18%; bottom: 14%; background: rgba(255, 255, 255, .1); }

        /* 右侧表单区 */
        .login-form-side {
            width: 480px;
            flex: none;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 64px;
            background: #fff;
            box-sizing: border-box;
        }
        .login-form-side h2 { font-size: 22px; font-weight: 500; margin: 0 0 4px; }
        .login-form-side .sub { font-size: 13px; color: var(--color-text-3); margin-bottom: 28px; }

        @media (max-width: 900px) {
            .login-brand-side { display: none; }
            .login-form-side { width: 100%; padding: 48px 24px; }
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-brand-side">
        <span class="logo-mark">{{ mb_substr((string) config('app.name', 'H'), 0, 1) }}</span>
        <h1>{{ config('app.name', 'HugeCMS') }}</h1>
        <p>基于 Laravel 的类 WordPress 建站系统 —— 内容建模、多模型动态字段、分类标签、媒体中心、回收站与权限体系，一站掌控。</p>
        <i class="deco c1"></i><i class="deco c2"></i><i class="deco c3"></i>
    </div>
    <div class="login-form-side">
        <h2>登录 {{ config('app.name', 'HugeCMS') }}</h2>
        <p class="sub">欢迎回来，请登录您的账号继续</p>
        <form method="POST" action="{{ route('admin.auth.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="请输入密码" required>
            </div>
            <div class="form-group flex items-center justify-between">
                <label class="inline-flex items-center mr-3" style="margin:0">
                    <input type="checkbox" name="remember" value="1"> 记住我
                </label>
                <a href="{{ route('admin.password.forgot') }}" style="font-size:13px">忘记密码？</a>
            </div>
            @if (session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert bg-danger">{{ $errors->first() }}</div>
            @endif
            <button type="submit" class="btn bg-primary-500 text-white w-full">登 录</button>
        </form>
    </div>
</div>
</body>
</html>
