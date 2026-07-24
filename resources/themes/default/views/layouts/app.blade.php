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

    {{-- Global Top Reading Progress Bar --}}
    <div id="reading-progress"></div>

    {{-- Minimal Craftsman Header --}}
    <header class="site-header">
        <div class="container">
            <div class="site-header-inner">

                {{-- Brand Logo --}}
                <div class="site-brand">
                    <a href="{{ url('/') }}" class="brand-link">
                        @php($logo = \App\Support\SiteSetting::get('logo'))
                        @if ($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}" class="brand-logo">
                        @else
                            <div class="brand-mark">
                                {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                            </div>
                        @endif
                        <span class="brand-name">
                            {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                        </span>
                    </a>
                </div>

                {{-- Center Main Nav --}}
                <nav class="nav-main">
                    @php($mainMenu = \App\Models\Menu::render('main'))
                    @forelse ($mainMenu as $item)
                        @include('partials.menu-item', ['item' => $item])
                    @empty
                        <a href="{{ url('/') }}" class="nav-link">首页</a>
                    @endforelse
                </nav>

                {{-- Controls: Search, Social & Dark Toggle --}}
                <div class="header-controls">

                    {{-- Search Modal Trigger Button --}}
                    <button type="button" class="search-trigger">
                        <svg class="icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="search-trigger-label">搜索</span>
                        <kbd class="search-kbd">⌘K</kbd>
                    </button>

                    {{-- Dark Mode Toggle --}}
                    @include('partials.theme-toggle')

                    <div class="header-auth">
                        @guest
                            <a href="{{ route('login') }}" class="header-auth-link">登录</a>
                            <a href="{{ route('register') }}" class="btn-primary btn-sm">注册</a>
                        @else
                            <a href="{{ route('member.dashboard') }}" class="header-auth-link">会员中心</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline-form">
                                @csrf
                                <button type="submit" class="logout-btn">退出</button>
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

    {{-- Editorial Minimal Footer --}}
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-grid">

                {{-- Left: Brand & Statement --}}
                <div class="footer-brand">
                    <div class="footer-brand-name">
                        <span>{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}</span>
                    </div>
                    <p class="footer-desc">
                        {{ \App\Support\SiteSetting::get('site_description', '专注于深度思考、技术探究与文字创作的高质感专栏。') }}
                    </p>
                </div>

                {{-- Right: Links & RSS --}}
                <div class="footer-links">
                    @php($footerMenu = \App\Models\Menu::render('footer'))
                    @if (! empty($footerMenu))
                        <div class="footer-col">
                            <h4 class="footer-heading">导航链接</h4>
                            <ul class="footer-list">
                                @foreach ($footerMenu as $item)
                                    <li>
                                        <a href="{{ $item['url'] }}" target="{{ $item['target'] }}">
                                            {{ $item['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="footer-col">
                        <h4 class="footer-heading">发现</h4>
                        <ul class="footer-list">
                            <li><a href="{{ url('/') }}">最新博文</a></li>
                            <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                        </ul>
                    </div>
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

    {{-- Search Overlay Modal Placeholder --}}
    <div id="search-modal" class="search-modal">
        <div class="search-modal-panel">
            <div class="search-modal-head">
                <div class="search-modal-field">
                    <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-modal-input" type="text" placeholder="搜索文章标题或关键词..." class="search-modal-input">
                </div>
                <button id="close-search-modal" class="search-modal-close">ESC</button>
            </div>

            <div class="search-modal-hint">
                输入关键词进行实时文章检索 (展位)
            </div>
        </div>
    </div>

</body>
</html>
