<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Support\SiteSetting::get('site_title', config('app.name')))</title>
    <meta name="description" content="@yield('description', \App\Support\SiteSetting::get('site_description', ''))">
    <meta name="keywords" content="@yield('keywords', \App\Support\SiteSetting::get('site_keywords', ''))">

    @stack('meta')
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600">
    @include('partials.theme-mode-init')

    <link rel="stylesheet" href="{{ theme_asset('css/app.css') }}">
    <script src="{{ theme_asset('js/app.js') }}" defer></script>
</head>
<body>

    <div id="reading-progress"></div>

    {{-- Ant Design 5 Pro Header Navigation Bar --}}
    <header class="site-header">
        <div class="container-wide">
            <div class="site-header-row">

                {{-- Brand Logo --}}
                <div class="site-brand-wrap">
                    <a href="{{ url('/') }}" class="site-brand">
                        @php($logo = \App\Support\SiteSetting::get('logo'))
                        @if ($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}" class="site-brand-logo">
                        @else
                            <div class="site-brand-badge">
                                {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                            </div>
                        @endif
                        <span class="site-brand-name">
                            {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                        </span>
                        <span class="tag visible-sm-flex">Enterprise</span>
                    </a>
                </div>

                {{-- Center Main Nav --}}
                <nav class="nav-main">
                    {{-- Mega Dropdown --}}
                    <div class="nav-dropdown">
                        <button type="button" class="nav-dropdown-toggle">
                            <span>产品矩阵</span>
                            <svg class="nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ url('/#features') }}" class="nav-dropdown-item">
                                <div class="nav-dropdown-item-icon">📝</div>
                                <div>
                                    <div class="nav-dropdown-item-title">内容编排引擎</div>
                                    <div class="nav-dropdown-item-desc">文章、树形多级分类与状态流</div>
                                </div>
                            </a>
                            <a href="{{ url('/#features') }}" class="nav-dropdown-item">
                                <div class="nav-dropdown-item-icon">🧱</div>
                                <div>
                                    <div class="nav-dropdown-item-title">可视化 Page Builder</div>
                                    <div class="nav-dropdown-item-desc">模块化区块拼装落地方案</div>
                                </div>
                            </a>
                            <a href="{{ url('/#features') }}" class="nav-dropdown-item">
                                <div class="nav-dropdown-item-icon">🔒</div>
                                <div>
                                    <div class="nav-dropdown-item-title">RBAC 细粒度权限</div>
                                    <div class="nav-dropdown-item-desc">预置角色与全量操作日志审计</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    @php($mainMenu = \App\Models\Menu::render('main'))
                    @forelse ($mainMenu as $item)
                        @include('partials.menu-item', ['item' => $item])
                    @empty
                        <a href="{{ url('/') }}" class="nav-link">首页</a>
                    @endforelse

                    <a href="{{ url('/#tech-specs') }}" class="nav-link">技术架构</a>
                    <a href="{{ url('/#faq') }}" class="nav-link">常见问题</a>
                </nav>

                {{-- Right Actions --}}
                <div class="header-actions">
                    {{-- Search Input Trigger --}}
                    <button type="button" class="search-trigger">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="visible-sm">搜索文档...</span>
                        <kbd class="search-kbd visible-sm-block">⌘K</kbd>
                    </button>

                    @include('partials.theme-toggle')

                    <div class="header-auth">
                        @guest
                            <a href="{{ route('login') }}" class="btn-default btn-sm">登录</a>
                            <a href="{{ route('register') }}" class="btn-primary btn-md">
                                免费试用
                            </a>
                        @else
                            <a href="{{ route('member.dashboard') }}" class="btn-default btn-sm">控制台</a>
                            <form method="POST" action="{{ route('logout') }}" class="form-inline">
                                @csrf
                                <button type="submit" class="btn-logout">退出</button>
                            </form>
                        @endguest
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="site-main">
        @yield('content')
    </main>

    {{-- AntD Footer Matrix --}}
    <footer class="site-footer">
        <div class="container-wide footer-inner">
            <div class="footer-grid">

                {{-- Column 1: Brand & Statement --}}
                <div class="footer-brand-col stack-3">
                    <div class="footer-brand">
                        <div class="footer-brand-badge">
                            {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                        </div>
                        <span>{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}</span>
                    </div>
                    <p class="footer-desc">
                        基于 Laravel 13 与 Filament 5 打造的新一代企业级通用内容管理系统。低门槛交付、私有化部署与极致扩展性。
                    </p>
                </div>

                {{-- Column 2: Products --}}
                <div class="footer-col stack-2">
                    <h4 class="footer-col-title">核心功能</h4>
                    <ul class="footer-col-list">
                        <li><a href="{{ url('/#features') }}">内容编排引擎</a></li>
                        <li><a href="{{ url('/#features') }}">可视化 Page Builder</a></li>
                        <li><a href="{{ url('/#features') }}">RBAC 细粒度权限</a></li>
                        <li><a href="{{ url('/#features') }}">Spatie 媒体资产</a></li>
                    </ul>
                </div>

                {{-- Column 3: Docs --}}
                <div class="footer-col stack-2">
                    <h4 class="footer-col-title">开发者资源</h4>
                    <ul class="footer-col-list">
                        <li><a href="{{ url('/#tech-specs') }}">技术架构对比</a></li>
                        <li><a href="{{ route('sitemap') }}">Sitemap XML</a></li>
                        <li><a href="{{ url('/#faq') }}">常见问题 FAQ</a></li>
                    </ul>
                </div>

                {{-- Column 4: System Links --}}
                <div class="footer-col stack-2">
                    <h4 class="footer-col-title">导航链接</h4>
                    @php($footerMenu = \App\Models\Menu::render('footer'))
                    @if (! empty($footerMenu))
                        <ul class="footer-col-list">
                            @foreach ($footerMenu as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" target="{{ $item['target'] }}">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="footer-col-list">
                            <li><a href="{{ url('/') }}">官网首页</a></li>
                            <li><a href="{{ route('login') }}">控制台登录</a></li>
                        </ul>
                    @endif
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}. {{ \App\Support\SiteSetting::get('copyright', 'All rights reserved.') }}</p>
                <div class="footer-icp">
                    @if (\App\Support\SiteSetting::get('icp'))
                        <span>{{ \App\Support\SiteSetting::get('icp') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- AntD Search Modal Overlay --}}
    <div id="search-modal" class="search-modal hidden">
        <div class="search-modal-panel">
            <div class="search-modal-header">
                <div class="search-modal-field">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-modal-input" type="text" placeholder="搜索功能、文档或动态..." class="search-modal-input">
                </div>
                <button id="close-search-modal" class="search-modal-esc">ESC</button>
            </div>
            <div class="search-modal-empty">
                输入关键词进行实时文章或功能检索
            </div>
        </div>
    </div>

</body>
</html>
