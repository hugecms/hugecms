<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'HugeCMS'))</title>
    @yield('meta')
    <link rel="stylesheet" href="https://unpkg.com/@arco-design/web-react@latest/dist/css/arco.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        .site-header { border-bottom: 1px solid #e5e7eb; background: #fff; }
        .site-nav { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
        .site-nav > li { list-style: none; position: relative; }
        .site-nav > li > a { display: block; padding: 14px 16px; color: #374151; }
        .site-nav > li:hover > a { color: #2563eb; }
        .site-nav .sub { display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #e5e7eb; min-width: 160px; z-index: 50; }
        .site-nav > li:hover .sub { display: block; }
        .site-nav .sub a { display: block; padding: 8px 16px; color: #374151; white-space: nowrap; }
        .site-footer { border-top: 1px solid #e5e7eb; background: #fff; margin-top: 40px; padding: 24px 0; color: #6b7280; font-size: 13px; }
        .article-item { border-bottom: 1px solid #f3f4f6; padding: 20px 0; }
        .comment-item { border: 1px solid #f3f4f6; border-radius: 6px; padding: 12px 16px; margin-bottom: 10px; }
    </style>
</head>
<body class="bg-gray-100">
@php
    $site = DB::table('sites')->where('status', 1)->orderBy('id')->first();
    $siteName = $site?->site_name ?? config('app.name', 'HugeCMS');
    $siteInfo = json_decode(DB::table('options')->where('option_key', 'site_info')->value('option_value') ?? '{}', true) ?: [];
    $navItems = DB::table('nav_items as ni')
        ->join('nav_menus as nm', 'ni.menu_id', '=', 'nm.id')
        ->where('nm.alias', 'main_nav')->where('ni.is_active', 1)
        ->orderBy('ni.sort')->select('ni.*')->get();
    $friendLinks = DB::table('friend_links')->where('status', 1)->orderBy('sort')->limit(20)->get();

    // 导航链接解析：custom 直链 / content → /{slug} / term → /{taxonomy_alias}/{slug}
    $navUrl = function (object $item): string {
        return match ($item->link_type) {
            'content' => '/' . (\App\Models\Content::query()->where('id', (int) $item->link_value)->value('slug') ?? ''),
            'term' => (function () use ($item): string {
                $row = DB::table('terms as t')
                    ->join('taxonomies as x', 't.taxonomy_id', '=', 'x.id')
                    ->where('t.id', (int) $item->link_value)
                    ->select('x.alias', 't.slug')
                    ->first();
                return $row ? "/{$row->alias}/{$row->slug}" : '/';
            })(),
            default => $item->link_value,
        };
    };
@endphp

<header class="site-header">
    <div class="container">
        <div class="flex items-center justify-between" style="padding:12px 0">
            <a href="{{ route('site.home') }}" class="text-2xl font-bold" style="color:#111827">{{ $siteName }}</a>
            <span class="text-gray-500 text-sm">{{ $siteInfo['slogan'] ?? '' }}</span>
        </div>
        <ul class="site-nav" style="margin:0;padding:0">
            @foreach ($navItems->where('parent_id', 0) as $item)
                @php $children = $navItems->where('parent_id', $item->id); @endphp
                <li>
                    <a href="{{ $navUrl($item) }}">{{ $item->title }}</a>
                    @if ($children->isNotEmpty())
                        <div class="sub">
                            @foreach ($children as $child)
                                <a href="{{ $navUrl($child) }}">{{ $child->title }}</a>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</header>

<main class="container" style="padding:24px 15px">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        @if ($friendLinks->isNotEmpty())
            <p>友情链接：
                @foreach ($friendLinks as $link)
                    <a href="{{ $link->site_url }}" target="_blank" rel="noopener">{{ $link->site_name }}</a>@if (!$loop->last) · @endif
                @endforeach
            </p>
        @endif
        <p>© {{ date('Y') }} {{ $siteName }}@if (!empty($siteInfo['icp_number'])) · {{ $siteInfo['icp_number'] }}@endif</p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
