<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
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
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-gray-100 antialiased">
    {{-- Header / Navigation --}}
    <header class="border-b border-gray-200 dark:border-gray-800">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                {{-- Logo / Site Name --}}
                <a href="{{ url('/') }}" class="text-lg font-semibold flex items-center gap-2">
                    @php($logo = \App\Support\SiteSetting::get('logo'))
                    @if ($logo)
                        <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}" class="h-8 w-auto">
                    @endif
                    {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                </a>

                {{-- Main Nav --}}
                @php($mainMenu = \App\Models\Menu::render('main'))
                <nav class="flex items-center gap-6 text-sm">
                    @forelse ($mainMenu as $item)
                        @include('partials.menu-item', ['item' => $item])
                    @empty
                        <a href="{{ url('/') }}" class="hover:text-gray-600 dark:hover:text-gray-300">首页</a>
                    @endforelse
                </nav>

                <div class="flex items-center gap-4 text-sm ml-4 border-l border-gray-200 dark:border-gray-800 pl-4">
                    @include('partials.theme-toggle')

                    @guest
                        <a href="{{ route('login') }}" class="hover:text-gray-600 dark:hover:text-gray-300">登录</a>
                        <a href="{{ route('register') }}" class="hover:text-gray-600 dark:hover:text-gray-300">注册</a>
                    @else
                        <a href="{{ route('member.dashboard') }}" class="hover:text-gray-600 dark:hover:text-gray-300">会员中心</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-gray-600 dark:hover:text-gray-300">退出</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="mx-auto max-w-6xl px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 space-y-4">
            @php($footerMenu = \App\Models\Menu::render('footer'))
            @if (! empty($footerMenu))
                <div class="flex flex-wrap items-center justify-center gap-4">
                    @foreach ($footerMenu as $item)
                        <a href="{{ $item['url'] }}" target="{{ $item['target'] }}"
                           class="hover:text-gray-700 dark:hover:text-gray-300">
                            {{ $item['title'] }}
                        </a>
                    @endforeach
                </div>
            @endif

            <p>&copy; {{ date('Y') }} {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}. {{ \App\Support\SiteSetting::get('copyright', 'All rights reserved.') }}</p>
            @if (\App\Support\SiteSetting::get('icp'))
                <p>{{ \App\Support\SiteSetting::get('icp') }}</p>
            @endif
            @if (\App\Support\SiteSetting::get('contact'))
                <p>{{ \App\Support\SiteSetting::get('contact') }}</p>
            @endif
        </div>
    </footer>
</body>
</html>
