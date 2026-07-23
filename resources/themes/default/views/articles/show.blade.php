@extends('layouts.app')

@section('title', $article->seo_title ?: $article->title)
@section('description', $article->seo_description ?: $article->excerpt)
@section('keywords', $article->seo_keywords)

@push('meta')
    @if ($article->getFirstMediaUrl('cover', 'og-image'))
        <meta property="og:image" content="{{ $article->getFirstMediaUrl('cover', 'og-image') }}">
    @endif
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:type" content="article">
@endpush

@section('content')
    <article class="max-w-3xl mx-auto space-y-8">
        {{-- Header --}}
        <header class="space-y-4">
            {{-- Categories --}}
            @if ($article->categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}"
                           class="text-sm px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl font-bold leading-tight">
                {{ $article->title }}
            </h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                <time datetime="{{ $article->published_at->toIso8601String() }}">
                    {{ $article->published_at->format('Y-m-d') }}
                </time>
                <span>·</span>
                <span>{{ $article->user->name }}</span>
                @if ($article->tags->isNotEmpty())
                    <span>·</span>
                    @foreach ($article->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}"
                           class="hover:text-gray-700 dark:hover:text-gray-300">#{{ $tag->name }}</a>
                    @endforeach
                @endif
            </div>
        </header>

        {{-- Cover Image --}}
        @if ($article->getFirstMediaUrl('cover', 'large'))
            <figure>
                <img src="{{ $article->getFirstMediaUrl('cover', 'large') }}"
                     alt="{{ $article->title }}"
                     class="w-full rounded-lg object-cover">
            </figure>
        @endif

        {{-- Excerpt (if exists, rendered as lead) --}}
        @if ($article->excerpt)
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ $article->excerpt }}
            </p>
        @endif

        {{-- Content --}}
        <div class="prose prose-gray dark:prose-invert max-w-none">
            {!! $article->content !!}
        </div>
    </article>
@endsection
