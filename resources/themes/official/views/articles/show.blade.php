@extends('layouts.app')

@section('title', $article->seo_title ?: $article->title . ' - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $article->seo_description ?: $article->summary)
@section('keywords', $article->seo_keywords)

@section('content')
<div id="article-content" class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    
    {{-- Header Meta --}}
    <header class="space-y-3 border-b border-[#f0f0f0] dark:border-[#303030] pb-6">
        <div class="flex items-center gap-2.5 text-xs text-slate-500">
            @if ($article->category)
                <a href="{{ route('category.show', $article->category->slug) }}" class="antd-tag">
                    {{ $article->category->name }}
                </a>
            @endif
            <time>{{ $article->published_at ? $article->published_at->format('Y年m月d日') : $article->created_at->format('Y年m月d日') }}</time>
        </div>

        <h1 class="text-2xl sm:text-4xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
            {{ $article->title }}
        </h1>
    </header>

    {{-- Cover Image --}}
    @php
        $largeCoverUrl = $article->getFirstMediaUrl('cover', 'large') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
    @endphp
    @if ($largeCoverUrl)
        <div class="overflow-hidden rounded-lg aspect-[21/9] bg-slate-100 dark:bg-slate-900 border border-[#f0f0f0] dark:border-[#303030]">
            <img src="{{ $largeCoverUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    {{-- Main Body --}}
    <article class="prose max-w-none text-slate-800 dark:text-slate-200 leading-relaxed text-sm sm:text-base">
        {!! $article->content !!}
    </article>

    {{-- Tags --}}
    @if ($article->tags && $article->tags->count() > 0)
        <div class="pt-6 border-t border-[#f0f0f0] dark:border-[#303030] flex items-center gap-2">
            <span class="text-xs text-slate-400">标签：</span>
            @foreach ($article->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="antd-tag">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
