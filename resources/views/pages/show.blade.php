@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description)
@section('keywords', $page->seo_keywords)

@push('meta')
    @if ($page->getFirstMediaUrl('cover', 'og-image'))
        <meta property="og:image" content="{{ $page->getFirstMediaUrl('cover', 'og-image') }}">
    @endif
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:type" content="article">
@endpush

@section('content')
    <article class="max-w-3xl mx-auto space-y-8">
        <header>
            <h1 class="text-3xl sm:text-4xl font-bold">{{ $page->title }}</h1>
        </header>

        @if ($page->getFirstMediaUrl('cover', 'large'))
            <figure>
                <img src="{{ $page->getFirstMediaUrl('cover', 'large') }}"
                     alt="{{ $page->title }}"
                     class="w-full rounded-lg object-cover">
            </figure>
        @endif

        <div class="prose prose-gray dark:prose-invert max-w-none">
            {!! $page->content !!}
        </div>
    </article>
@endsection
