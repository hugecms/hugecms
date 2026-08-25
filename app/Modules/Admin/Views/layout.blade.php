<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '管理后台 - HugeCMS')</title>
    <link href="{{ asset('static/bootstrap-5.3.8/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/css/hugecms.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/css/hugecms-admin.css') }}" rel="stylesheet" />
</head>
<body class="admin-layout">
    <header class="admin-header">
        <div class="admin-page-title">@yield('page-title', '仪表盘')</div>
        <ul class="navbar-nav flex-row align-items-center gap-2 m-0">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-body py-1" data-bs-toggle="dropdown" role="button">
                    <span class="admin-brand-dot">{{ mb_substr(auth()->user()?->name ?? '?', 0, 1) }}</span>
                    <span>{{ auth()->user()?->name }}</span>
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
    </header>

    <aside class="admin-sider">
        <div class="admin-brand">
            <span class="admin-brand-dot">H</span>
            <span>HugeCMS</span>
        </div>
        @php
            $menuGroups = app(\App\Services\MenuService::class)->getSidebarMenus();
        @endphp
        <nav class="admin-menu">
            @foreach ($menuGroups as $group)
                @if ($group['route'] !== '')
                    <a href="{{ route($group['route']) }}"
                       class="admin-menu-item {{ request()->routeIs($group['route']) ? 'active' : '' }}">
                        @include('admin::icons', ['name' => $group['icon']])
                        <span>{{ $group['name'] }}</span>
                    </a>
                @else
                    <div class="admin-menu-group-title">{{ $group['name'] }}</div>
                    <div class="admin-menu-group-items">
                        @foreach ($group['children'] as $child)
                            @if ($child['route'] !== '')
                                <a href="{{ route($child['route']) }}"
                                   class="admin-menu-item {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                    @include('admin::icons', ['name' => $child['icon']])
                                    <span>{{ $child['name'] }}</span>
                                </a>
                            @else
                                <span class="admin-menu-item disabled" aria-disabled="true" title="未开放">
                                    @include('admin::icons', ['name' => $child['icon']])
                                    <span>{{ $child['name'] }}</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endforeach
        </nav>
    </aside>

    <main class="admin-content">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="{{ asset('static/jquery-4.0.0/jquery.min.js') }}"></script>
    <script src="{{ asset('static/bootstrap-5.3.8/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('static/vue-3.5.41/vue.global.prod.js') }}"></script>
    @yield('scripts')
</body>
</html>
