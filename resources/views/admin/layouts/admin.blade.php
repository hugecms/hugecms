<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理面板') - {{ config('app.name', 'HugeCMS') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/zui/zui.css') }}">
    <style>
        body { background: #f5f6f7; }
        .admin-sidebar { position: fixed; top: 0; bottom: 0; left: 0; width: 220px; overflow-y: auto; background: #2b3548; color: #c6cad3; z-index: 100; }
        .admin-sidebar .brand { display: block; padding: 18px 20px; color: #fff; font-size: 18px; font-weight: bold; border-bottom: 1px solid #3a4358; text-decoration: none; }
        .admin-sidebar .nav-group { padding: 10px 0 4px; }
        .admin-sidebar .nav-group > .group-title { padding: 4px 20px; font-size: 12px; color: #7d8698; }
        .admin-sidebar .nav-group > ul { list-style: none; margin: 0; padding: 0; }
        .admin-sidebar .nav-group > ul > li > a { display: block; padding: 8px 20px 8px 32px; color: #c6cad3; text-decoration: none; font-size: 13px; }
        .admin-sidebar .nav-group > ul > li > a:hover { background: #34405a; color: #fff; }
        .admin-sidebar .nav-group > ul > li > a.active { background: #3aa0ff; color: #fff; }
        .admin-main { margin-left: 220px; }
        .admin-topbar { background: #fff; border-bottom: 1px solid #e5e6e7; padding: 0 20px; height: 50px; line-height: 50px; }
        .admin-topbar .user-menu { float: right; }
        .admin-content { padding: 20px; }
    </style>
</head>
<body>
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">{{ config('app.name', 'HugeCMS') }}</a>
        @include('admin.layouts.partials.sidebar')
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <span class="text-gray-500">@yield('title', '管理面板')</span>
            <div class="user-menu">
                <span class="text-gray-500">{{ auth()->user()->name ?? '游客' }}</span>
                <form method="POST" action="{{ route('admin.auth.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-link">退出</button>
                </form>
            </div>
        </header>
        <main class="admin-content">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('assets/zui/zui.js') }}"></script>
    <script>
        // 后台 API 取数助手：约定响应结构 {code, message, data}
        window.adminApi = {
            async request(url, method, data) {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: data === undefined ? undefined : JSON.stringify(data),
                });
                return res.json();
            },
            get(url) { return this.request(url, 'GET'); },
            post(url, data = {}) { return this.request(url, 'POST', data); },
            put(url, data = {}) { return this.request(url, 'PUT', data); },
            // 兼容 {list} / {rows} / 裸数组 三种分页返回形态
            rows(res) {
                const d = res && res.data;
                if (Array.isArray(d)) return d;
                return (d && (d.list || d.rows)) || [];
            },
            esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
            },
        };
    </script>
    @stack('scripts')
</body>
</html>
