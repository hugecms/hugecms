<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '用户中心 - HugeCMS')</title>
    <link href="{{ asset('static/bootstrap-5.3.8/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/css/hugecms.css') }}" rel="stylesheet" />
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="/">HugeCMS</a>
            <ul class="navbar-nav flex-row align-items-center gap-3 ms-auto">
                <li class="nav-item">
                    <a href="/" class="nav-link">首页</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.home') }}" class="nav-link">我的主页</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button">
                        {{ auth()->user()?->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="dropdown-item">退出登录</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-top bg-white py-3">
        <div class="container text-center text-muted small">© {{ now()->year }} HugeCMS</div>
    </footer>

    <script src="{{ asset('static/jquery-4.0.0/jquery.min.js') }}"></script>
    <script src="{{ asset('static/bootstrap-5.3.8/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('static/vue-3.5.41/vue.global.prod.js') }}"></script>
    @yield('scripts')
</body>
</html>
