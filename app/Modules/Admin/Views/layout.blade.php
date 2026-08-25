<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '管理后台 - HugeCMS')</title>
    <link href="{{ asset('static/bootstrap-5.3.8/css/bootstrap.min.css') }}" rel="stylesheet" />
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">HugeCMS 管理后台</a>
            <ul class="navbar-nav flex-row align-items-center gap-3">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" role="button">
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

    <div class="d-flex" style="min-height: calc(100vh - 56px);">
        <aside class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 220px;">
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">仪表盘</a>
                </li>
            </ul>
        </aside>

        <main class="flex-grow-1 p-4 overflow-auto">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('static/jquery-4.0.0/jquery.min.js') }}"></script>
    <script src="{{ asset('static/bootstrap-5.3.8/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('static/vue-3.5.41/vue.global.prod.js') }}"></script>
    @yield('scripts')
</body>
</html>
