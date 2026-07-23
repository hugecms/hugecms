@php
    use App\Enums\ContentStatus;
    use App\Models\Article;

    $articles = Article::where('status', ContentStatus::Published->value)
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->when(! empty($data['category_id']), fn ($query) => $query->whereHas('categories', fn ($q) => $q->where('categories.id', $data['category_id'])))
        ->orderBy('published_at', 'desc')
        ->take((int) ($data['count'] ?? 3))
        ->get();
@endphp

@component('blocks._layout', ['data' => $data])
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($articles as $article)
            <article class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-md transition-shadow">
                @if (($data['show_cover'] ?? true) && $article->getFirstMediaUrl('cover', 'medium'))
                    <a href="{{ route('article.show', $article->slug) }}">
                        <img src="{{ $article->getFirstMediaUrl('cover', 'medium') }}"
                             alt="{{ $article->title }}"
                             class="w-full h-48 object-cover">
                    </a>
                @endif
                <div class="p-4 space-y-2">
                    <h3 class="text-lg font-semibold">
                        <a href="{{ route('article.show', $article->slug) }}"
                           class="hover:text-gray-600 dark:hover:text-gray-300">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <time datetime="{{ $article->published_at->toIso8601String() }}"
                          class="block text-xs text-gray-500 dark:text-gray-400">
                        {{ $article->published_at->format('Y-m-d') }}
                    </time>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400">该分类下暂无文章</div>
        @endforelse
    </div>
@endcomponent
