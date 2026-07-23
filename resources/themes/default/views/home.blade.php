@extends('layouts.app')

@section('title', \App\Support\SiteSetting::get('site_title', config('app.name')))
@section('description', \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 space-y-12">
    
    {{-- Editorial Hero Header Section --}}
    <section class="border-b border-zinc-200 dark:border-zinc-800 pb-10 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-serif font-black tracking-tight text-zinc-900 dark:text-white leading-tight">
                    {{ \App\Support\SiteSetting::get('site_name', config('app.name')) }}
                </h1>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base leading-relaxed">
                    {{ \App\Support\SiteSetting::get('site_description', '专注技术探究、个人思考与文字创作的高品质独立博客。') }}
                </p>
            </div>

            {{-- Social Icons Bar --}}
            <div class="flex items-center gap-3 text-zinc-500 dark:text-zinc-400 text-xs font-medium">
                <a href="https://github.com" target="_blank" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition-colors" title="GitHub">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                </a>
                <a href="https://x.com" target="_blank" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition-colors" title="X / Twitter">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="{{ route('sitemap') }}" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition-colors" title="RSS Feed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19 7.38 20 6.18 20C5 20 4 19 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/></svg>
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
            <section class="space-y-4">
                <div class="flex items-center justify-between text-xs font-bold tracking-wider text-zinc-400 uppercase">
                    <span>— 精选推荐</span>
                </div>

                <div class="group card-craft rounded-2xl p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    @if ($featuredCoverUrl)
                        <div class="lg:col-span-6 overflow-hidden rounded-xl aspect-[16/10] bg-zinc-100 dark:bg-zinc-900">
                            <img src="{{ $featuredCoverUrl }}" 
                                 alt="{{ $featured->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @endif

                    <div class="{{ $featuredCoverUrl ? 'lg:col-span-6' : 'lg:col-span-12' }} space-y-4">
                        <div class="flex items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                            @if ($featured->category)
                                <a href="{{ route('category.show', $featured->category->slug) }}" 
                                   class="font-semibold text-amber-700 dark:text-amber-400 hover:underline">
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

                        <h2 class="text-2xl sm:text-3xl font-bold font-serif text-zinc-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors leading-tight">
                            <a href="{{ route('article.show', $featured->slug) }}">
                                {{ $featured->title }}
                            </a>
                        </h2>

                        @if ($featured->summary)
                            <p class="text-zinc-600 dark:text-zinc-400 text-sm line-clamp-3 leading-relaxed">
                                {{ $featured->summary }}
                            </p>
                        @endif

                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">作者：{{ $featured->author->name ?? '博主' }}</span>
                            <a href="{{ route('article.show', $featured->slug) }}" class="text-xs font-semibold text-zinc-900 dark:text-white hover:text-amber-700 dark:hover:text-amber-400 transition-colors">
                                阅读全文 →
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Main Content Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- Articles List --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-center justify-between text-xs font-bold tracking-wider text-zinc-400 uppercase pb-2 border-b border-zinc-100 dark:border-zinc-800">
                    <span>最新文章</span>
                </div>

                <div class="space-y-6">
                    @php
                        $listToDisplay = ($articles->currentPage() === 1 && $featured) ? $regularArticles : $articles;
                    @endphp

                    @forelse ($listToDisplay as $article)
                        @php
                            $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                        @endphp
                        <article class="group card-craft rounded-xl p-5 sm:p-6 flex flex-col sm:flex-row gap-6 items-start">
                            @if ($coverUrl)
                                <div class="w-full sm:w-44 flex-shrink-0 overflow-hidden rounded-lg aspect-[16/10] bg-zinc-100 dark:bg-zinc-900">
                                    <img src="{{ $coverUrl }}" 
                                         alt="{{ $article->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif

                            <div class="flex-grow space-y-2">
                                <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                    @if ($article->category)
                                        <a href="{{ route('category.show', $article->category->slug) }}" class="font-medium text-amber-700 dark:text-amber-400 hover:underline">
                                            {{ $article->category->name }}
                                        </a>
                                        <span>•</span>
                                    @endif
                                    <time>{{ $article->published_at ? $article->published_at->format('m-d') : $article->created_at->format('m-d') }}</time>
                                    <span>•</span>
                                    <span>{{ max(1, ceil(mb_strlen(strip_tags($article->content)) / 400)) }} 分钟</span>
                                </div>

                                <h3 class="font-bold font-serif text-lg text-zinc-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors line-clamp-2">
                                    <a href="{{ route('article.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>

                                @if ($article->summary)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                        {{ $article->summary }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="py-12 text-center text-xs text-zinc-400">
                            暂无已发布的文章
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="pt-4">
                    {{ $articles->links() }}
                </div>
            </div>

            {{-- Sidebar Column --}}
            <aside class="lg:col-span-4 space-y-8">
                
                {{-- Newsletter Subscription Box (Static Placeholder) --}}
                <div class="card-craft rounded-2xl p-6 space-y-4">
                    <div class="space-y-1">
                        <h3 class="font-serif font-bold text-sm text-zinc-900 dark:text-white">订阅更新 (Newsletter)</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            定期接收最新的思考与专栏博文，绝无垃圾邮件。
                        </p>
                    </div>
                    <form onsubmit="event.preventDefault(); alert('订阅成功！(静态功能展位)');" class="space-y-2">
                        <input type="email" placeholder="your@email.com" required
                               class="w-full px-3 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 text-xs text-zinc-900 dark:text-white focus:outline-none focus:border-zinc-400">
                        <button type="submit" class="w-full py-2 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition-opacity">
                            订阅
                        </button>
                    </form>
                </div>

                {{-- Categories Widget --}}
                @php($allCategories = \App\Models\Category::withCount('articles')->get())
                @if ($allCategories->count() > 0)
                    <div class="card-craft rounded-2xl p-6 space-y-3">
                        <h3 class="font-serif font-bold text-sm text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-2">
                            文章分类
                        </h3>
                        <div class="space-y-1">
                            @foreach ($allCategories as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}" 
                                   class="flex items-center justify-between py-1.5 px-2 rounded-lg text-xs text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 hover:text-zinc-900 dark:hover:text-white transition-colors">
                                    <span>{{ $cat->name }}</span>
                                    <span class="font-mono text-[10px] text-zinc-400">{{ $cat->articles_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>
        </div>
    @else
        <div class="py-20 text-center text-xs text-zinc-400">
            暂无已发布的文章
        </div>
    @endif
</div>
@endsection
