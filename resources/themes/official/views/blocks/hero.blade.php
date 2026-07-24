<section class="card block-hero">
    @if (! empty($data['badge']))
        <span class="tag">
            {{ $data['badge'] }}
        </span>
    @endif

    @if (! empty($data['title']))
        <h2 class="block-hero-title">
            {{ $data['title'] }}
        </h2>
    @endif

    @if (! empty($data['subtitle']))
        <p class="block-hero-subtitle">
            {{ $data['subtitle'] }}
        </p>
    @endif

    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div class="block-hero-action">
            <a href="{{ $data['button_url'] }}" class="btn-primary btn-cta">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</section>
