@extends('layouts.app')

@section('title', '#' . $tag->name . ' - 标签 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
<div class="container archive-page">

    {{-- Header --}}
    <div class="page-hero">
        <div class="page-hero-kicker page-hero-kicker-mono">
            # 标签检索
        </div>
        <h1 class="page-title">
            {{ $tag->name }}
        </h1>
        <div class="page-count">
            关联 {{ $articles->total() }} 篇文章
        </div>
    </div>

    {{-- Articles Grid --}}
    <div class="archive-list">
        <div class="cards-grid">
            @forelse ($articles as $article)
                @php
                    $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                @endphp
                <article class="card post-card">
                    <div class="post-card-main">
                        @if ($coverUrl)
                            <div class="cover-frame aspect-16-10">
                                <img src="{{ $coverUrl }}"
                                     alt="{{ $article->title }}"
                                     class="cover-img">
                            </div>
                        @endif

                        <div class="post-card-date">
                            <time>{{ $article->published_at ? $article->published_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</time>
                        </div>

                        <h3 class="post-card-title clamp-2">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h3>

                        @if ($article->summary)
                            <p class="post-card-summary clamp-2">
                                {{ $article->summary }}
                            </p>
                        @endif
                    </div>

                    <div class="post-card-footer">
                        <span>{{ $article->author->name ?? '博主' }}</span>
                        <a href="{{ route('article.show', $article->slug) }}">阅读 →</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    此标签下暂无文章。
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
