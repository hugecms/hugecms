@php
    $limit = $data['limit'] ?? 6;
    $blockArticles = \App\Models\Article::published()->latest('published_at')->take($limit)->get();
@endphp

@if ($blockArticles->count() > 0)
    <div class="space-y-6 py-6">
        @if (! empty($data['title']))
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                {{ $data['title'] }}
            </h3>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($blockArticles as $article)
                <article class="group bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-5 flex flex-col justify-between space-y-4 shadow-sm hover:shadow-md transition-all blog-card-hover">
                    <div class="space-y-3">
                        @if ($article->cover_image)
                            <div class="overflow-hidden rounded-xl aspect-[16/9] bg-slate-100 dark:bg-slate-700">
                                <img src="{{ asset('storage/'.$article->cover_image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif

                        <h4 class="font-bold text-base text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h4>

                        @if ($article->summary)
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $article->summary }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
                        <span>{{ $article->author->name ?? '博主' }}</span>
                        <a href="{{ route('article.show', $article->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">阅读 →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
