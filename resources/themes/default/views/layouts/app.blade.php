<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Support\SiteSetting::get('site_title', config('app.name')))</title>
    <meta name="description" content="@yield('description', \App\Support\SiteSetting::get('site_description', ''))">
    <meta name="keywords" content="@yield('keywords', \App\Support\SiteSetting::get('site_keywords', ''))">

    @stack('meta')
    @fonts
    @include('partials.theme-mode-init')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(theme_vite_entries())
    @endif
</head>
<body class="bg-paper text-zinc-900 dark:text-zinc-100 antialiased selection:bg-amber-500 selection:text-white flex flex-col min-h-screen font-sans">
    
    {{-- Global Top Reading Progress Bar --}}
    <div id="reading-progress"></div>

    {{-- Minimal Craftsman Header --}}
    <header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-200/80 dark:border-zinc-800/80 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                
                {{-- Brand Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-bold tracking-tight text-zinc-900 dark:text-white text-lg hover:opacity-80 transition-opacity">
                        @php($logo = \App\Support\SiteSetting::get('logo'))
                        @if ($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}" class="h-7 w-auto">
                        @else
                            <div class="w-7 h-7 rounded bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-serif font-black flex items-center justify-center text-sm">
                                {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                            </div>
                        @endif
                        <span class="font-serif">
                            {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                        </span>
                    </a>
                </div>

                {{-- Center Main Nav --}}
                <nav class="hidden md:flex items-center gap-6 text-xs font-medium">
                    @php($mainMenu = \App\Models\Menu::render('main'))
                    @forelse ($mainMenu as $item)
                        @include('partials.menu-item', ['item' => $item])
                    @empty
                        <a href="{{ url('/') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">首页</a>
                    @endforelse
                </nav>

                {{-- Controls: Search, Social & Dark Toggle --}}
                <div class="flex items-center gap-3">
                    
                    {{-- Search Modal Trigger Button --}}
                    <button type="button" class="search-trigger flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-xs font-medium text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="hidden sm:inline">搜索</span>
                        <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono text-zinc-400 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-800 rounded border border-zinc-200 dark:border-zinc-700">⌘K</kbd>
                    </button>

                    {{-- Dark Mode Toggle --}}
                    @include('partials.theme-toggle')

                    <div class="hidden sm:flex items-center gap-3 pl-3 border-l border-zinc-200 dark:border-zinc-800 text-xs font-medium">
                        @guest
                            <a href="{{ route('login') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">登录</a>
                            <a href="{{ route('register') }}" class="bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 px-3 py-1.5 rounded-lg hover:opacity-90 transition-opacity">注册</a>
                        @else
                            <a href="{{ route('member.dashboard') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">会员中心</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">退出</button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Editorial Minimal Footer --}}
    <footer class="mt-20 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-zinc-100 dark:border-zinc-800/80">
                
                {{-- Left: Brand & Statement --}}
                <div class="md:col-span-5 space-y-4">
                    <div class="font-serif font-bold text-base text-zinc-900 dark:text-white flex items-center gap-2">
                        <span>{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}</span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-sm leading-relaxed">
                        {{ \App\Support\SiteSetting::get('site_description', '专注于深度思考、技术探究与文字创作的高质感专栏。') }}
                    </p>
                </div>

                {{-- Right: Links & RSS --}}
                <div class="md:col-span-7 flex flex-wrap gap-8 justify-between md:justify-end text-xs">
                    @php($footerMenu = \App\Models\Menu::render('footer'))
                    @if (! empty($footerMenu))
                        <div class="space-y-2">
                            <h4 class="font-bold text-zinc-900 dark:text-white">导航链接</h4>
                            <ul class="space-y-1.5 text-zinc-500 dark:text-zinc-400">
                                @foreach ($footerMenu as $item)
                                    <li>
                                        <a href="{{ $item['url'] }}" target="{{ $item['target'] }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">
                                            {{ $item['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <h4 class="font-bold text-zinc-900 dark:text-white">发现</h4>
                        <ul class="space-y-1.5 text-zinc-500 dark:text-zinc-400">
                            <li><a href="{{ url('/') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">最新博文</a></li>
                            <li><a href="{{ route('sitemap') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-400 dark:text-zinc-500">
                <p>&copy; {{ date('Y') }} {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}. {{ \App\Support\SiteSetting::get('copyright', 'All rights reserved.') }}</p>
                <div class="flex items-center gap-4">
                    @if (\App\Support\SiteSetting::get('icp'))
                        <span>{{ \App\Support\SiteSetting::get('icp') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- Search Overlay Modal Placeholder --}}
    <div id="search-modal" class="fixed inset-0 z-50 bg-zinc-950/60 backdrop-blur-sm hidden items-start justify-center pt-20 px-4">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 w-full max-w-xl rounded-2xl p-4 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2 flex-grow">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-modal-input" type="text" placeholder="搜索文章标题或关键词..." class="w-full bg-transparent text-sm text-zinc-900 dark:text-white focus:outline-none placeholder-zinc-400">
                </div>
                <button id="close-search-modal" class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 px-2 py-1">ESC</button>
            </div>
            
            <div class="py-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
                输入关键词进行实时文章检索 (展位)
            </div>
        </div>
    </div>

</body>
</html>
