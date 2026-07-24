@php
    $limit = $data['limit'] ?? 6;
    $blockArticles = \App\Models\Article::published()->latest('published_at')->take($limit)->get();
@endphp

@if ($blockArticles->count() > 0)
    <div class="block-articles">
        @if (! empty($data['title']))
            <h3 class="block-title">
                <span class="block-title-dot"></span>
                {{ $data['title'] }}
            </h3>
        @endif

        <div class="cards-grid">
            @foreach ($blockArticles as $article)
                <article class="block-card">
                    <div class="block-card-main">
                        @if ($article->cover_image)
                            <div class="block-card-cover cover-frame aspect-16-9">
                                <img src="{{ asset('storage/'.$article->cover_image) }}"
                                     alt="{{ $article->title }}"
                                     class="cover-img">
                            </div>
                        @endif

                        <h4 class="block-card-title clamp-2">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h4>

                        @if ($article->summary)
                            <p class="block-card-summary clamp-2">
                                {{ $article->summary }}
                            </p>
                        @endif
                    </div>

                    <div class="block-card-footer">
                        <span>{{ $article->author->name ?? '博主' }}</span>
                        <a href="{{ route('article.show', $article->slug) }}">阅读 →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
