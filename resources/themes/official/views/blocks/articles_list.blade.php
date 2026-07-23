@php
    $limit = $data['limit'] ?? 6;
    $blockArticles = \App\Models\Article::published()->latest('published_at')->take($limit)->get();
@endphp

@if ($blockArticles->count() > 0)
    <div class="space-y-4 py-4">
        @if (! empty($data['title']))
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                {{ $data['title'] }}
            </h3>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($blockArticles as $article)
                @php
                    $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                @endphp
                <article class="antd-card p-5 flex flex-col justify-between space-y-3">
                    <div class="space-y-2">
                        @if ($coverUrl)
                            <div class="overflow-hidden rounded aspect-[16/10] bg-slate-100 dark:bg-slate-900">
                                <img src="{{ $coverUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <h4 class="font-bold text-sm text-slate-900 dark:text-white hover:text-[#1677FF] transition-colors line-clamp-2">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h4>
                    </div>

                    <div class="pt-2 border-t border-[#f0f0f0] dark:border-[#303030] text-xs font-semibold text-[#1677FF]">
                        <a href="{{ route('article.show', $article->slug) }}">阅读详情 →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
