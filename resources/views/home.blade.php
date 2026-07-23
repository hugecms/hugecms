@extends('layouts.app')

@section('title', \App\Support\SiteSetting::get('site_title', config('app.name')))
@section('description', \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
    <div class="space-y-8">
        {{-- Hero --}}
        <div class="text-center py-12">
            <h1 class="text-4xl font-bold">{{ config('app.name') }}</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                欢迎来到我们的网站
            </p>
        </div>

        {{-- Article List --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($articles as $article)
                <article class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-md transition-shadow">
                    {{-- Cover --}}
                    @if ($article->getFirstMediaUrl('cover', 'medium'))
                        <a href="{{ route('article.show', $article->slug) }}">
                            <img src="{{ $article->getFirstMediaUrl('cover', 'medium') }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-48 object-cover">
                        </a>
                    @endif

                    <div class="p-4 space-y-2">
                        {{-- Categories --}}
                        @if ($article->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                                @foreach ($article->categories as $category)
                                    <a href="{{ route('category.show', $category->slug) }}"
                                       class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Title --}}
                        <h2 class="text-lg font-semibold">
                            <a href="{{ route('article.show', $article->slug) }}"
                               class="hover:text-gray-600 dark:hover:text-gray-300">
                                {{ $article->title }}
                            </a>
                        </h2>

                        {{-- Excerpt --}}
                        @if ($article->excerpt)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        {{-- Meta --}}
                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 pt-2">
                            <time datetime="{{ $article->published_at->toIso8601String() }}">
                                {{ $article->published_at->format('Y-m-d') }}
                            </time>
                            @if ($article->tags->isNotEmpty())
                                <span>·</span>
                                @foreach ($article->tags as $tag)
                                    <a href="{{ route('tag.show', $tag->slug) }}"
                                       class="hover:text-gray-700 dark:hover:text-gray-300">#{{ $tag->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                    暂无已发布的文章
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
