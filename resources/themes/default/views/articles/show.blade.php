@extends('layouts.app')

@section('title', $article->seo_title ?: $article->title . ' - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $article->seo_description ?: $article->summary)
@section('keywords', $article->seo_keywords)

@section('content')
<div id="article-content" class="container article-page">
    <div class="article-layout">

        {{-- Main Article Content Column --}}
        <main class="article-main">

            {{-- Header Meta --}}
            <header class="article-header">
                <div class="article-meta">
                    @if ($article->category)
                        <a href="{{ route('category.show', $article->category->slug) }}"
                           class="accent-link">
                            {{ $article->category->name }}
                        </a>
                        <span>•</span>
                    @endif
                    <time>
                        {{ $article->published_at ? $article->published_at->format('Y年m月d日') : $article->created_at->format('Y年m月d日') }}
                    </time>
                    <span>•</span>
                    <span>预计阅读时间 {{ max(1, ceil(mb_strlen(strip_tags($article->content)) / 400)) }} 分钟</span>
                </div>

                <h1 class="article-title">
                    {{ $article->title }}
                </h1>

                <div class="article-author">
                    <div class="avatar avatar-sm">
                        {{ mb_substr($article->author->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <span class="author-name">{{ $article->author->name ?? '博主' }}</span>
                        <span class="author-role">独立撰稿人</span>
                    </div>
                </div>
            </header>

            {{-- Featured Image --}}
            @php
                $largeCoverUrl = $article->getFirstMediaUrl('cover', 'large') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
            @endphp
            @if ($largeCoverUrl)
                <div class="article-cover cover-frame aspect-21-9">
                    <img src="{{ $largeCoverUrl }}"
                         alt="{{ $article->title }}"
                         class="cover-img">
                </div>
            @endif

            {{-- Summary Quote --}}
            @if ($article->summary)
                <blockquote class="article-summary">
                    <strong>导读：</strong>{{ $article->summary }}
                </blockquote>
            @endif

            {{-- Article Body --}}
            <article class="prose article-body">
                {!! $article->content !!}
            </article>

            {{-- Interaction Bar: Like / Applaud & Share --}}
            <div class="interaction-bar">

                {{-- Applaud Button --}}
                <div class="interaction-group">
                    <button id="like-article-btn" type="button" class="like-btn">
                        <span>👏 赞赏 / 点赞</span>
                        <span id="like-count" class="like-count">12</span>
                    </button>
                </div>

                {{-- Copy Link & Tags --}}
                <div class="interaction-group">
                    @if ($article->tags && $article->tags->count() > 0)
                        @foreach ($article->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}"
                               class="tag-chip">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    @endif

                    <button id="copy-article-link" type="button" class="btn-outline">
                        分享链接
                    </button>
                </div>
            </div>

            {{-- Author Bio Card --}}
            <div class="author-box">
                <div class="avatar avatar-lg avatar-invert">
                    {{ mb_substr($article->author->name ?? 'A', 0, 1) }}
                </div>
                <div class="author-box-text">
                    <h4 class="author-box-title">
                        关于作者：{{ $article->author->name ?? '博主' }}
                    </h4>
                    <p class="author-box-desc">
                        记录技术探索与思考，用代码与文字表达观点。感谢您的阅读与支持。
                    </p>
                </div>
            </div>

            {{-- Comments & Discussion Section (High-Fidelity Placeholder) --}}
            <section class="comments-section">
                <div>
                    <h3 class="comments-title">读者讨论 (3)</h3>
                </div>

                {{-- Post Comment Form --}}
                <form onsubmit="event.preventDefault(); alert('评论提交成功！(静态功能展位)');" class="comment-form">
                    <textarea rows="3" placeholder="写下您的思考或讨论..." required
                              class="form-textarea"></textarea>
                    <div class="comment-form-foot">
                        <span class="hint-text">支持 Markdown 简单语法</span>
                        <button type="submit" class="btn-primary">
                            发表评论
                        </button>
                    </div>
                </form>

                {{-- Comments List Placeholder --}}
                <div class="comment-list">
                    <div class="comment-card">
                        <div class="comment-meta">
                            <span class="comment-author">Alex Chen</span>
                            <time class="comment-time">2 小时前</time>
                        </div>
                        <p class="comment-text">
                            文章分析得非常透彻！特别是关于模块解耦部分，获益匪浅。
                        </p>
                    </div>

                    <div class="comment-card">
                        <div class="comment-meta">
                            <span class="comment-author">Developer_01</span>
                            <time class="comment-time">昨天 18:30</time>
                        </div>
                        <p class="comment-text">
                            排版质感极佳，阅读体验非常舒服，期待下一篇！
                        </p>
                    </div>
                </div>
            </section>

        </main>

        {{-- Sidebar / Sticky Table of Contents (TOC) Column --}}
        <aside class="article-sidebar">
            <div class="toc-sticky">

                {{-- Table of Contents Card --}}
                <div class="card toc-card">
                    <h3 class="toc-title">
                        文章目录 (TOC)
                    </h3>
                    <ul id="article-toc-list" class="toc-list">
                        {{-- Populated dynamically via app.js --}}
                    </ul>
                </div>

            </div>
        </aside>

    </div>
</div>
@endsection
