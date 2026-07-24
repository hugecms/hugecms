@php
    $limit = $data['limit'] ?? 6;
    $blockArticles = \App\Models\Article::published()->latest('published_at')->take($limit)->get();
@endphp

@if ($blockArticles->count() > 0)
    <div class="block-articles">
        @if (! empty($data['title']))
            <h3 class="block-articles-title">
                {{ $data['title'] }}
            </h3>
        @endif

        <div class="articles-grid-list">
            @foreach ($blockArticles as $article)
                @php
                    $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                @endphp
                <article class="card article-card">
                    <div class="article-card-body">
                        @if ($coverUrl)
                            <div class="article-card-cover">
                                <img src="{{ $coverUrl }}" alt="{{ $article->title }}">
                            </div>
                        @endif

                        <h4 class="article-card-title">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h4>
                    </div>

                    <div class="article-card-footer">
                        <a href="{{ route('article.show', $article->slug) }}">阅读详情 →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
