@extends('layouts.app')

@section('title', $article->seo_title ?: $article->title . ' - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $article->seo_description ?: $article->summary)
@section('keywords', $article->seo_keywords)

@section('content')
<div id="article-content" class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- Main Article Content Column --}}
        <main class="lg:col-span-8 space-y-10">
            
            {{-- Header Meta --}}
            <header class="space-y-4 border-b border-zinc-200 dark:border-zinc-800 pb-8">
                <div class="flex items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                    @if ($article->category)
                        <a href="{{ route('category.show', $article->category->slug) }}" 
                           class="font-semibold text-amber-700 dark:text-amber-400 hover:underline">
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

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-zinc-900 dark:text-white tracking-tight leading-tight">
                    {{ $article->title }}
                </h1>

                <div class="flex items-center gap-3 text-xs text-zinc-600 dark:text-zinc-400 pt-2">
                    <div class="w-7 h-7 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center font-bold text-zinc-700 dark:text-zinc-300">
                        {{ mb_substr($article->author->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $article->author->name ?? '博主' }}</span>
                        <span class="text-zinc-400 text-[11px] block">独立撰稿人</span>
                    </div>
                </div>
            </header>

            {{-- Featured Image --}}
            @php
                $largeCoverUrl = $article->getFirstMediaUrl('cover', 'large') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
            @endphp
            @if ($largeCoverUrl)
                <div class="overflow-hidden rounded-xl aspect-[21/9] bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                    <img src="{{ $largeCoverUrl }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover">
                </div>
            @endif

            {{-- Summary Quote --}}
            @if ($article->summary)
                <blockquote class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-900/60 border-l-4 border-amber-600 text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">
                    <strong>导读：</strong>{{ $article->summary }}
                </blockquote>
            @endif

            {{-- Article Body --}}
            <article class="prose max-w-none text-zinc-800 dark:text-zinc-200 leading-relaxed space-y-6 text-base">
                {!! $article->content !!}
            </article>

            {{-- Interaction Bar: Like / Applaud & Share --}}
            <div class="pt-8 border-t border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-4">
                
                {{-- Applaud Button --}}
                <div class="flex items-center gap-3">
                    <button id="like-article-btn" type="button" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-amber-600 dark:hover:border-amber-400 text-xs font-semibold transition-all">
                        <span>👏 赞赏 / 点赞</span>
                        <span id="like-count" class="px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 font-mono text-[11px]">12</span>
                    </button>
                </div>

                {{-- Copy Link & Tags --}}
                <div class="flex items-center gap-2">
                    @if ($article->tags && $article->tags->count() > 0)
                        @foreach ($article->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" 
                               class="px-2.5 py-1 rounded-md text-xs font-mono bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 hover:text-amber-700 dark:hover:text-amber-400">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    @endif

                    <button id="copy-article-link" type="button" class="px-3 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-800 text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                        分享链接
                    </button>
                </div>
            </div>

            {{-- Author Bio Card --}}
            <div class="p-6 rounded-2xl bg-zinc-50 dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-serif font-black flex items-center justify-center text-lg flex-shrink-0">
                    {{ mb_substr($article->author->name ?? 'A', 0, 1) }}
                </div>
                <div class="space-y-1 text-xs">
                    <h4 class="font-bold text-zinc-900 dark:text-white text-sm">
                        关于作者：{{ $article->author->name ?? '博主' }}
                    </h4>
                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        记录技术探索与思考，用代码与文字表达观点。感谢您的阅读与支持。
                    </p>
                </div>
            </div>

            {{-- Comments & Discussion Section (High-Fidelity Placeholder) --}}
            <section class="space-y-6 pt-6 border-t border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <h3 class="font-serif font-bold text-lg text-zinc-900 dark:text-white">读者讨论 (3)</h3>
                </div>

                {{-- Post Comment Form --}}
                <form onsubmit="event.preventDefault(); alert('评论提交成功！(静态功能展位)');" class="space-y-3">
                    <textarea rows="3" placeholder="写下您的思考或讨论..." required
                              class="w-full p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-400"></textarea>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] text-zinc-400">支持 Markdown 简单语法</span>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity">
                            发表评论
                        </button>
                    </div>
                </form>

                {{-- Comments List Placeholder --}}
                <div class="space-y-4 pt-2">
                    <div class="p-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-white dark:bg-zinc-900 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-zinc-900 dark:text-white">Alex Chen</span>
                            <time class="text-zinc-400 text-[11px]">2 小时前</time>
                        </div>
                        <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                            文章分析得非常透彻！特别是关于模块解耦部分，获益匪浅。
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-white dark:bg-zinc-900 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-zinc-900 dark:text-white">Developer_01</span>
                            <time class="text-zinc-400 text-[11px]">昨天 18:30</time>
                        </div>
                        <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                            排版质感极佳，阅读体验非常舒服，期待下一篇！
                        </p>
                    </div>
                </div>
            </section>

        </main>

        {{-- Sidebar / Sticky Table of Contents (TOC) Column --}}
        <aside class="hidden lg:block lg:col-span-4">
            <div class="sticky top-24 space-y-6">
                
                {{-- Table of Contents Card --}}
                <div class="card-craft rounded-2xl p-5 space-y-3">
                    <h3 class="font-serif font-bold text-xs text-zinc-400 uppercase tracking-wider">
                        文章目录 (TOC)
                    </h3>
                    <ul id="article-toc-list" class="space-y-1">
                        {{-- Populated dynamically via app.js --}}
                    </ul>
                </div>

            </div>
        </aside>

    </div>
</div>
@endsection
