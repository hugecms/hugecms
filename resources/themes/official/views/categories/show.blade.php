@extends('layouts.app')

@section('title', $category->seo_title ?: $category->name . ' - 分类 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $category->seo_description ?: $category->description)
@section('keywords', $category->seo_keywords)

@section('content')
<div class="container-wide archive-page">
    <div class="archive-header">
        <span class="tag">分类归档</span>
        <h1 class="archive-title">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="archive-desc">{{ $category->description }}</p>
        @endif
    </div>

    <div class="articles-grid">
        @forelse ($articles as $article)
            @php
                $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
            @endphp
            <article class="card article-card">
                <div class="article-card-body">
                    @if ($coverUrl)
                        <div class="article-card-cover">
                            <img src="{{ $coverUrl }}" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <h3 class="article-card-title">
                        <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                </div>
                <a href="{{ route('article.show', $article->slug) }}" class="article-card-link">阅读全文 →</a>
            </article>
        @empty
            <div class="empty-state">该分类下暂无已发布的文章</div>
        @endforelse
    </div>
</div>
@endsection
