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
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] antialiased">
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
                @php($navCategories = App\Models\Category::whereNull('parent_id')->with('children')->get())
                @php($navPages = App\Models\Page::whereNull('parent_id')->where('status', 'published')->get())
                <nav class="flex items-center gap-6 text-sm">
                    <a href="{{ url('/') }}" class="hover:text-gray-600 dark:hover:text-gray-300">首页</a>

                    @foreach ($navCategories as $category)
                        <div class="relative group">
                            <a href="{{ route('category.show', $category->slug) }}"
                               class="hover:text-gray-600 dark:hover:text-gray-300 inline-flex items-center gap-1">
                                {{ $category->name }}
                                @if ($category->children->isNotEmpty())
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </a>
                            @if ($category->children->isNotEmpty())
                                <div class="absolute left-0 top-full hidden group-hover:block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-md shadow-lg min-w-[160px] py-1 z-50">
                                    @foreach ($category->children as $child)
                                        <a href="{{ route('category.show', $child->slug) }}"
                                           class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @foreach ($navPages as $page)
                        <a href="{{ route('page.show', $page->slug) }}"
                           class="hover:text-gray-600 dark:hover:text-gray-300">
                            {{ $page->title }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-4 text-sm ml-4 border-l border-gray-200 dark:border-gray-800 pl-4">
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
        <div class="mx-auto max-w-6xl px-4 sm:px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 space-y-2">
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
