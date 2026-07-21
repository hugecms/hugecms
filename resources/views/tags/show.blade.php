@extends('layouts.app')

@section('title', "标签: {$tag->name}")
@section('description', $tag->description)

@section('content')
    <div class="space-y-8">
        <div class="space-y-4">
            <h1 class="text-3xl font-bold">#{{ $tag->name }}</h1>
            @if ($tag->description)
                <p class="text-gray-600 dark:text-gray-400">{{ $tag->description }}</p>
            @endif
            <p class="text-sm text-gray-500 dark:text-gray-400">
                共 {{ $articles->total() }} 篇文章
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($articles as $article)
                <article class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-md transition-shadow">
                    @if ($article->getFirstMediaUrl('cover', 'medium'))
                        <a href="{{ route('article.show', $article->slug) }}">
                            <img src="{{ $article->getFirstMediaUrl('cover', 'medium') }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-48 object-cover">
                        </a>
                    @endif

                    <div class="p-4 space-y-2">
                        <h2 class="text-lg font-semibold">
                            <a href="{{ route('article.show', $article->slug) }}"
                               class="hover:text-gray-600 dark:hover:text-gray-300">
                                {{ $article->title }}
                            </a>
                        </h2>
                        <time datetime="{{ $article->published_at->toIso8601String() }}"
                              class="block text-xs text-gray-500 dark:text-gray-400 pt-2">
                            {{ $article->published_at->format('Y-m-d') }}
                        </time>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                    该标签下暂无文章
                </div>
            @endforelse
        </div>

        {{ $articles->links() }}
    </div>
@endsection
