@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', $page->seo_keywords ?: \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    
    {{-- Header --}}
    <header class="border-b border-zinc-200 dark:border-zinc-800 pb-6 space-y-2">
        <h1 class="text-3xl sm:text-4xl font-serif font-black text-zinc-900 dark:text-white tracking-tight">
            {{ $page->title }}
        </h1>
        <p class="text-xs text-zinc-400 font-mono">
            更新于 {{ $page->updated_at ? $page->updated_at->format('Y-m-d') : $page->created_at->format('Y-m-d') }}
        </p>
    </header>

    {{-- Page Blocks or Rich Text --}}
    @if ($page->blocks && is_array($page->blocks) && count($page->blocks) > 0)
        <div class="space-y-10">
            @foreach ($page->blocks as $block)
                @includeIf('blocks.' . ($block['type'] ?? 'rich_text'), ['data' => $block['data'] ?? []])
            @endforeach
        </div>
    @else
        <article class="prose max-w-none text-zinc-800 dark:text-zinc-200 leading-relaxed text-base">
            {!! $page->content !!}
        </article>
    @endif
</div>
@endsection
