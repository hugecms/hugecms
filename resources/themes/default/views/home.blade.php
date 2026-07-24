@extends('layouts.app')

@section('title', \App\Support\SiteSetting::get('site_title', config('app.name')))
@section('description', \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="container home-page">

    {{-- Editorial Hero Header Section --}}
    <section class="home-hero">
        <div class="home-hero-inner">
            <div class="home-hero-text">
                <h1 class="home-title">
                    {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                </h1>
                <p class="home-desc">
                    {{ \App\Support\SiteSetting::get('site_description', '专注技术探究、个人思考与文字创作的高品质独立博客。') }}
                </p>
            </div>

            {{-- Social Icons Bar --}}
            <div class="social-bar">
                <a href="https://github.com" target="_blank" class="icon-btn" title="GitHub">
                    <svg class="icon-sm" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                </a>
                <a href="https://x.com" target="_blank" class="icon-btn" title="X / Twitter">
                    <svg class="icon-sm" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="{{ route('sitemap') }}" class="icon-btn" title="RSS Feed">
                    <svg class="icon-sm" fill="currentColor" viewBox="0 0 24 24"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19 7.38 20 6.18 20C5 20 4 19 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/></svg>
                </a>
            </div>
        </div>
    </section>

    @if ($articles->count() > 0)
        @php
            $featured = $articles->first();
            $regularArticles = $articles->slice(1);
        @endphp

        {{-- Featured Article Section (Page 1) --}}
        @if ($articles->currentPage() === 1 && $featured)
            @php
                $featuredCoverUrl = $featured->getFirstMediaUrl('cover', 'medium') ?: ($featured->cover_image ? asset('storage/'.$featured->cover_image) : null);
            @endphp
            <section class="featured-section">
                <div class="section-label">
                    <span>— 精选推荐</span>
                </div>

                <div class="card featured-card">
                    @if ($featuredCoverUrl)
                        <div class="featured-cover cover-frame aspect-16-10">
                            <img src="{{ $featuredCoverUrl }}"
                                 alt="{{ $featured->title }}"
                                 class="cover-img">
                        </div>
                    @endif

                    <div class="featured-body {{ $featuredCoverUrl ? '' : 'featured-body-full' }}">
                        <div class="article-meta">
                            @if ($featured->category)
                                <a href="{{ route('category.show', $featured->category->slug) }}"
                                   class="accent-link">
                                    {{ $featured->category->name }}
                                </a>
                                <span>•</span>
                            @endif
                            <time>
                                {{ $featured->published_at ? $featured->published_at->format('Y-m-d') : $featured->created_at->format('Y-m-d') }}
                            </time>
                            <span>•</span>
                            <span>约 {{ max(1, ceil(mb_strlen(strip_tags($featured->content)) / 400)) }} 分钟阅读</span>
                        </div>

                        <h2 class="featured-title">
                            <a href="{{ route('article.show', $featured->slug) }}">
                                {{ $featured->title }}
                            </a>
                        </h2>

                        @if ($featured->summary)
                            <p class="featured-summary clamp-3">
                                {{ $featured->summary }}
                            </p>
                        @endif

                        <div class="featured-footer">
                            <span class="meta-text">作者：{{ $featured->author->name ?? '博主' }}</span>
                            <a href="{{ route('article.show', $featured->slug) }}" class="read-more">
                                阅读全文 →
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Main Content Section --}}
        <div class="home-layout">

            {{-- Articles List --}}
            <div class="home-main">
                <div class="section-label section-label-bordered">
                    <span>最新文章</span>
                </div>

                <div class="article-list">
                    @php
                        $listToDisplay = ($articles->currentPage() === 1 && $featured) ? $regularArticles : $articles;
                    @endphp

                    @forelse ($listToDisplay as $article)
                        @php
                            $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                        @endphp
                        <article class="card article-card">
                            @if ($coverUrl)
                                <div class="article-card-cover cover-frame aspect-16-10">
                                    <img src="{{ $coverUrl }}"
                                         alt="{{ $article->title }}"
                                         class="cover-img">
                                </div>
                            @endif

                            <div class="article-card-body">
                                <div class="article-meta article-meta-sm">
                                    @if ($article->category)
                                        <a href="{{ route('category.show', $article->category->slug) }}" class="accent-link">
                                            {{ $article->category->name }}
                                        </a>
                                        <span>•</span>
                                    @endif
                                    <time>{{ $article->published_at ? $article->published_at->format('m-d') : $article->created_at->format('m-d') }}</time>
                                    <span>•</span>
                                    <span>{{ max(1, ceil(mb_strlen(strip_tags($article->content)) / 400)) }} 分钟</span>
                                </div>

                                <h3 class="article-card-title clamp-2">
                                    <a href="{{ route('article.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>

                                @if ($article->summary)
                                    <p class="article-card-summary clamp-2">
                                        {{ $article->summary }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            暂无已发布的文章
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrap">
                    {{ $articles->links() }}
                </div>
            </div>

            {{-- Sidebar Column --}}
            <aside class="home-sidebar">

                {{-- Newsletter Subscription Box (Static Placeholder) --}}
                <div class="card widget">
                    <div class="widget-head">
                        <h3 class="widget-title">订阅更新 (Newsletter)</h3>
                        <p class="widget-desc">
                            定期接收最新的思考与专栏博文，绝无垃圾邮件。
                        </p>
                    </div>
                    <form onsubmit="event.preventDefault(); alert('订阅成功！(静态功能展位)');" class="newsletter-form">
                        <input type="email" placeholder="your@email.com" required
                               class="form-input form-input-sm">
                        <button type="submit" class="btn-primary btn-block">
                            订阅
                        </button>
                    </form>
                </div>

                {{-- Categories Widget --}}
                @php($allCategories = \App\Models\Category::withCount('articles')->get())
                @if ($allCategories->count() > 0)
                    <div class="card widget">
                        <h3 class="widget-title widget-title-bordered">
                            文章分类
                        </h3>
                        <div class="category-list">
                            @foreach ($allCategories as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}"
                                   class="category-item">
                                    <span>{{ $cat->name }}</span>
                                    <span class="category-count">{{ $cat->articles_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>
        </div>
    @else
        <div class="empty-state empty-state-lg">
            暂无已发布的文章
        </div>
    @endif
</div>
@endsection
