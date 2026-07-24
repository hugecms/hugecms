@extends('layouts.app')

@section('title', $article->seo_title ?: $article->title . ' - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $article->seo_description ?: $article->summary)
@section('keywords', $article->seo_keywords)

@section('content')
<div id="article-content" class="container-narrow article-page">

    {{-- Header Meta --}}
    <header class="article-header">
        <div class="article-meta">
            @if ($article->category)
                <a href="{{ route('category.show', $article->category->slug) }}" class="tag">
                    {{ $article->category->name }}
                </a>
            @endif
            <time>{{ $article->published_at ? $article->published_at->format('Y年m月d日') : $article->created_at->format('Y年m月d日') }}</time>
        </div>

        <h1 class="article-title">
            {{ $article->title }}
        </h1>
    </header>

    {{-- Cover Image --}}
    @php
        $largeCoverUrl = $article->getFirstMediaUrl('cover', 'large') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
    @endphp
    @if ($largeCoverUrl)
        <div class="article-cover">
            <img src="{{ $largeCoverUrl }}" alt="{{ $article->title }}">
        </div>
    @endif

    {{-- Main Body --}}
    <article class="prose article-body">
        {!! $article->content !!}
    </article>

    {{-- Tags --}}
    @if ($article->tags && $article->tags->count() > 0)
        <div class="article-tags">
            <span class="article-tags-label">标签：</span>
            @foreach ($article->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="tag">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
