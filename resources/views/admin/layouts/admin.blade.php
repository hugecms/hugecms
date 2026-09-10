<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理面板') - {{ config('app.name', 'HugeCMS') }}</title>
    {{-- 后台样式自包含：Arco Design token 体系（public/assets/css/app.css），不依赖组件库 --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <span class="brand-mark">{{ mb_substr((string) config('app.name', 'H'), 0, 1) }}</span>
            <span class="brand-name">{{ config('app.name', 'HugeCMS') }}</span>
        </a>
        @include('admin.layouts.partials.sidebar')
    </aside>
    <div class="admin-main">
        @php
            // 顶栏面包屑：从菜单结构定位当前分组/页面（与 sidebar partial 同一判定逻辑）
            $crumbGroup = null;
            $crumbItem = null;
            foreach (\App\Http\Controllers\Admin\IndexController::menu() as $group) {
                foreach ($group['items'] as $item) {
                    if (request()->routeIs($item['active'])) {
                        $crumbGroup = $group['title'];
                        $crumbItem = $item['title'];
                        break 2;
                    }
                }
            }
        @endphp
        <header class="admin-topbar">
            <div class="topbar-left">
                <button type="button" class="collapse-btn" id="sider-collapse" title="收起/展开菜单">☰</button>
                <nav class="topbar-breadcrumb">
                    @if ($crumbGroup)
                        <span>{{ $crumbGroup }}</span>
                        <span class="sep">/</span>
                        <span class="current">{{ $crumbItem }}</span>
                    @else
                        <span class="current">@yield('title', '管理面板')</span>
                    @endif
                </nav>
            </div>
            <div class="user-menu">
                <span class="avatar">{{ mb_substr((string) (auth()->user()->name ?? '客'), 0, 1) }}</span>
                <span>{{ auth()->user()->name ?? '游客' }}</span>
                <form method="POST" action="{{ route('admin.auth.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-link">退出</button>
                </form>
            </div>
        </header>
        <main class="admin-content">
            @yield('content')
        </main>
        <footer class="page-footer">Copyright © {{ date('Y') }} {{ config('app.name', 'HugeCMS') }}</footer>
    </div>
    <script>
        // 侧栏折叠（48px 图标态，Arco Pro 行为）
        document.getElementById('sider-collapse').addEventListener('click', () => {
            document.body.classList.toggle('sider-collapsed');
        });

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
            // 每页条数（分页器选择后更新，供各列表页 load 默认值使用）
            pageSize: 20,
            // 兼容 {data} / {list} / {rows} / 裸数组 四种分页返回形态
            rows(res) {
                const d = res && res.data;
                if (Array.isArray(d)) return d;
                return (d && (d.data || d.list || d.rows)) || [];
            },
            esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
            },
            // 分页器渲染（Pro Table 底右风格）：pager(el, res, reloadFn)
            pager(el, res, reload) {
                const box = typeof el === 'string' ? document.getElementById(el) : el;
                if (!box) return;
                const d = res?.data ?? {};
                const page = Number(d.current ?? d.currentPage ?? d.current_page ?? 1);
                const total = Number(d.total ?? 0);
                const size = Number(d.pageSize ?? d.perPage ?? d.per_page ?? 10) || 10;
                const last = Math.max(1, Number(d.lastPage ?? d.last_page ?? Math.ceil(total / size)));
                box.innerHTML = '';
                if (last <= 1) return;

                const mk = (label, target, opts = {}) => {
                    const b = document.createElement('button');
                    b.className = 'btn';
                    b.textContent = label;
                    if (opts.active) b.classList.add('bg-primary-500', 'text-white');
                    b.disabled = !!opts.disabled;
                    b.onclick = () => reload(target, size);
                    return b;
                };

                box.append(mk('上一页', page - 1, {disabled: page <= 1}));

                // 页码按钮（最多展示 7 个，含首尾与省略号）
                const pages = [];
                if (last <= 7) {
                    for (let i = 1; i <= last; i++) pages.push(i);
                } else {
                    pages.push(1);
                    const start = Math.max(2, page - 2);
                    const end = Math.min(last - 1, page + 2);
                    if (start > 2) pages.push('…');
                    for (let i = start; i <= end; i++) pages.push(i);
                    if (end < last - 1) pages.push('…');
                    pages.push(last);
                }
                pages.forEach(p => {
                    if (p === '…') {
                        const s = document.createElement('span');
                        s.className = 'pager-info';
                        s.textContent = '…';
                        box.append(s);
                    } else {
                        box.append(mk(p, p, {active: p === page, disabled: p === page}));
                    }
                });

                box.append(mk('下一页', page + 1, {disabled: page >= last}));

                // 每页条数选择器
                const sizeSel = document.createElement('select');
                sizeSel.className = 'form-control';
                [10, 20, 50, 100].forEach(n => {
                    const o = document.createElement('option');
                    o.value = n;
                    o.textContent = `${n} 条/页`;
                    if (n === size) o.selected = true;
                    sizeSel.append(o);
                });
                sizeSel.onchange = () => {
                    adminApi.pageSize = Number(sizeSel.value);
                    reload(1, Number(sizeSel.value));
                };
                box.append(sizeSel);

                const info = document.createElement('span');
                info.className = 'pager-info';
                info.textContent = `共 ${total} 条`;
                box.append(info);
            },
        };
    </script>
    @stack('scripts')
</body>
</html>
