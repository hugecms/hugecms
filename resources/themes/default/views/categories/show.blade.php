@extends('layouts.app')

@section('title', $category->seo_title ?: $category->name . ' - 分类 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $category->seo_description ?: $category->description)
@section('keywords', $category->seo_keywords)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 space-y-10">
    
    {{-- Header --}}
    <div class="border-b border-zinc-200 dark:border-zinc-800 pb-8 space-y-3">
        <div class="text-xs text-amber-700 dark:text-amber-400 font-semibold tracking-wider uppercase">
            分类专栏
        </div>
        <h1 class="text-3xl font-serif font-black text-zinc-900 dark:text-white tracking-tight">
            {{ $category->name }}
        </h1>
        @if ($category->description)
            <p class="text-zinc-500 dark:text-zinc-400 text-xs sm:text-sm max-w-xl leading-relaxed">
                {{ $category->description }}
            </p>
        @endif
        <div class="text-xs text-zinc-400 font-mono">
            共收录 {{ $articles->total() }} 篇文章
        </div>
    </div>

    {{-- Articles Grid --}}
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($articles as $article)
                @php
                    $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                @endphp
                <article class="group card-craft rounded-xl p-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        @if ($coverUrl)
                            <div class="overflow-hidden rounded-lg aspect-[16/10] bg-zinc-100 dark:bg-zinc-900">
                                <img src="{{ $coverUrl }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif

                        <div class="text-xs text-zinc-400">
                            <time>{{ $article->published_at ? $article->published_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</time>
                        </div>

                        <h3 class="font-bold font-serif text-base text-zinc-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors line-clamp-2">
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

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-400">
                        <span>{{ $article->author->name ?? '博主' }}</span>
                        <a href="{{ route('article.show', $article->slug) }}" class="hover:text-zinc-900 dark:hover:text-white font-medium">阅读 →</a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-xs text-zinc-400">
                    该分类下暂无文章。
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pt-4">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
