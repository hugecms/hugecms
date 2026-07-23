@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', $page->seo_keywords ?: \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    <header class="border-b border-[#f0f0f0] dark:border-[#303030] pb-6 space-y-2">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
            {{ $page->title }}
        </h1>
    </header>

    @if ($page->blocks && is_array($page->blocks) && count($page->blocks) > 0)
        <div class="space-y-8">
            @foreach ($page->blocks as $block)
                @includeIf('blocks.' . ($block['type'] ?? 'rich_text'), ['data' => $block['data'] ?? []])
            @endforeach
        </div>
    @else
        <article class="prose max-w-none text-slate-800 dark:text-slate-200 leading-relaxed text-sm sm:text-base">
            {!! $page->content !!}
        </article>
    @endif
</div>
@endsection
