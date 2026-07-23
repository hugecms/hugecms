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
<body class="bg-[#fafafa] dark:bg-[#141414] text-slate-800 dark:text-slate-200 antialiased selection:bg-[#1677FF] selection:text-white flex flex-col min-h-screen font-sans">
    
    <div id="reading-progress"></div>

    {{-- Ant Design 5 Pro Header Navigation Bar --}}
    <header class="sticky top-0 z-40 w-full bg-white/90 dark:bg-[#141414]/90 backdrop-blur-md border-b border-[#f0f0f0] dark:border-[#303030] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                {{-- Brand Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-bold tracking-tight text-slate-900 dark:text-white hover:opacity-85 transition-opacity">
                        @php($logo = \App\Support\SiteSetting::get('logo'))
                        @if ($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}" class="h-7 w-auto">
                        @else
                            <div class="w-7 h-7 rounded bg-[#1677FF] text-white font-bold flex items-center justify-center text-sm shadow-sm">
                                {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                            </div>
                        @endif
                        <span class="text-base font-bold">
                            {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                        </span>
                        <span class="antd-tag hidden sm:inline-flex">Enterprise</span>
                    </a>
                </div>

                {{-- Center Main Nav --}}
                <nav class="hidden lg:flex items-center gap-7 text-xs font-medium">
                    {{-- Mega Dropdown --}}
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-1 font-medium text-slate-700 dark:text-slate-300 hover:text-[#1677FF] dark:hover:text-[#4096FF] py-2 transition-colors">
                            <span>产品矩阵</span>
                            <svg class="w-3.5 h-3.5 opacity-60 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-1 w-72 bg-white dark:bg-[#1f1f1f] rounded-lg shadow-antd-s2 border border-[#f0f0f0] dark:border-[#303030] p-2 hidden group-hover:block transition-all z-50">
                            <a href="{{ url('/#features') }}" class="flex items-start gap-3 p-2.5 rounded hover:bg-[#E6F4FF]/50 dark:hover:bg-slate-800 transition-colors">
                                <div class="w-7 h-7 rounded bg-[#1677FF]/10 text-[#1677FF] flex items-center justify-center flex-shrink-0 font-bold text-xs">📝</div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">内容编排引擎</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">文章、树形多级分类与状态流</div>
                                </div>
                            </a>
                            <a href="{{ url('/#features') }}" class="flex items-start gap-3 p-2.5 rounded hover:bg-[#E6F4FF]/50 dark:hover:bg-slate-800 transition-colors">
                                <div class="w-7 h-7 rounded bg-[#1677FF]/10 text-[#1677FF] flex items-center justify-center flex-shrink-0 font-bold text-xs">🧱</div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">可视化 Page Builder</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">模块化区块拼装落地方案</div>
                                </div>
                            </a>
                            <a href="{{ url('/#features') }}" class="flex items-start gap-3 p-2.5 rounded hover:bg-[#E6F4FF]/50 dark:hover:bg-slate-800 transition-colors">
                                <div class="w-7 h-7 rounded bg-[#1677FF]/10 text-[#1677FF] flex items-center justify-center flex-shrink-0 font-bold text-xs">🔒</div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">RBAC 细粒度权限</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">预置角色与全量操作日志审计</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    @php($mainMenu = \App\Models\Menu::render('main'))
                    @forelse ($mainMenu as $item)
                        @include('partials.menu-item', ['item' => $item])
                    @empty
                        <a href="{{ url('/') }}" class="font-medium text-slate-700 dark:text-slate-300 hover:text-[#1677FF]">首页</a>
                    @endforelse

                    <a href="{{ url('/#tech-specs') }}" class="font-medium text-slate-700 dark:text-slate-300 hover:text-[#1677FF]">技术架构</a>
                    <a href="{{ url('/#faq') }}" class="font-medium text-slate-700 dark:text-slate-300 hover:text-[#1677FF]">常见问题</a>
                </nav>

                {{-- Right Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Search Input Trigger --}}
                    <button type="button" class="search-trigger flex items-center gap-2 px-2.5 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] text-xs text-slate-500 dark:text-slate-400 bg-white dark:bg-[#1f1f1f] hover:border-[#4096FF] transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="hidden sm:inline">搜索文档...</span>
                        <kbd class="hidden sm:inline-block px-1 py-0.2 text-[10px] font-mono text-slate-400 bg-slate-100 dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700">⌘K</kbd>
                    </button>

                    @include('partials.theme-toggle')

                    <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-[#f0f0f0] dark:border-[#303030] text-xs">
                        @guest
                            <a href="{{ route('login') }}" class="antd-btn-default px-3 py-1.5">登录</a>
                            <a href="{{ route('register') }}" class="antd-btn-primary px-3.5 py-1.5">
                                免费试用
                            </a>
                        @else
                            <a href="{{ route('member.dashboard') }}" class="antd-btn-default px-3 py-1.5">控制台</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-400 hover:text-rose-600 px-2 py-1 transition-colors">退出</button>
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

    {{-- AntD Footer Matrix --}}
    <footer class="mt-20 border-t border-[#f0f0f0] dark:border-[#303030] bg-white dark:bg-[#141414] transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-10 border-b border-[#f0f0f0] dark:border-[#303030]">
                
                {{-- Column 1: Brand & Statement --}}
                <div class="lg:col-span-2 space-y-3">
                    <div class="flex items-center gap-2 font-bold text-base text-slate-900 dark:text-white">
                        <div class="w-6 h-6 rounded bg-[#1677FF] text-white flex items-center justify-center font-bold text-xs">
                            {{ mb_substr(\App\Support\SiteSetting::get('site_name', config('app.name')), 0, 1) }}
                        </div>
                        <span>{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed">
                        基于 Laravel 13 与 Filament 5 打造的新一代企业级通用内容管理系统。低门槛交付、私有化部署与极致扩展性。
                    </p>
                </div>

                {{-- Column 2: Products --}}
                <div class="space-y-2 text-xs">
                    <h4 class="font-bold text-slate-900 dark:text-white">核心功能</h4>
                    <ul class="space-y-1.5 text-slate-500 dark:text-slate-400">
                        <li><a href="{{ url('/#features') }}" class="hover:text-[#1677FF] transition-colors">内容编排引擎</a></li>
                        <li><a href="{{ url('/#features') }}" class="hover:text-[#1677FF] transition-colors">可视化 Page Builder</a></li>
                        <li><a href="{{ url('/#features') }}" class="hover:text-[#1677FF] transition-colors">RBAC 细粒度权限</a></li>
                        <li><a href="{{ url('/#features') }}" class="hover:text-[#1677FF] transition-colors">Spatie 媒体资产</a></li>
                    </ul>
                </div>

                {{-- Column 3: Docs --}}
                <div class="space-y-2 text-xs">
                    <h4 class="font-bold text-slate-900 dark:text-white">开发者资源</h4>
                    <ul class="space-y-1.5 text-slate-500 dark:text-slate-400">
                        <li><a href="{{ url('/#tech-specs') }}" class="hover:text-[#1677FF] transition-colors">技术架构对比</a></li>
                        <li><a href="{{ route('sitemap') }}" class="hover:text-[#1677FF] transition-colors">Sitemap XML</a></li>
                        <li><a href="{{ url('/#faq') }}" class="hover:text-[#1677FF] transition-colors">常见问题 FAQ</a></li>
                    </ul>
                </div>

                {{-- Column 4: System Links --}}
                <div class="space-y-2 text-xs">
                    <h4 class="font-bold text-slate-900 dark:text-white">导航链接</h4>
                    @php($footerMenu = \App\Models\Menu::render('footer'))
                    @if (! empty($footerMenu))
                        <ul class="space-y-1.5 text-slate-500 dark:text-slate-400">
                            @foreach ($footerMenu as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" target="{{ $item['target'] }}" class="hover:text-[#1677FF] transition-colors">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="space-y-1.5 text-slate-500 dark:text-slate-400">
                            <li><a href="{{ url('/') }}" class="hover:text-[#1677FF] transition-colors">官网首页</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-[#1677FF] transition-colors">控制台登录</a></li>
                        </ul>
                    @endif
                </div>

            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}. {{ \App\Support\SiteSetting::get('copyright', 'All rights reserved.') }}</p>
                <div class="flex items-center gap-4">
                    @if (\App\Support\SiteSetting::get('icp'))
                        <span>{{ \App\Support\SiteSetting::get('icp') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- AntD Search Modal Overlay --}}
    <div id="search-modal" class="fixed inset-0 z-50 bg-black/45 backdrop-blur-xs hidden items-start justify-center pt-20 px-4">
        <div class="bg-white dark:bg-[#1f1f1f] border border-[#f0f0f0] dark:border-[#303030] w-full max-w-xl rounded-lg p-4 shadow-antd-s2 space-y-4">
            <div class="flex items-center justify-between border-b border-[#f0f0f0] dark:border-[#303030] pb-3">
                <div class="flex items-center gap-2 flex-grow">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-modal-input" type="text" placeholder="搜索功能、文档或动态..." class="w-full bg-transparent text-xs text-slate-900 dark:text-white focus:outline-none placeholder-slate-400">
                </div>
                <button id="close-search-modal" class="text-xs font-mono text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded">ESC</button>
            </div>
            <div class="py-8 text-center text-xs text-slate-400">
                输入关键词进行实时文章或功能检索
            </div>
        </div>
    </div>

</body>
</html>
