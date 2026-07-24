<div class="block-cta">
    @if (! empty($data['title']))
        <h3 class="block-cta-title">{{ $data['title'] }}</h3>
    @endif
    @if (! empty($data['description']))
        <p class="block-cta-desc">{{ $data['description'] }}</p>
    @endif
    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div>
            <a href="{{ $data['button_url'] }}" class="btn-indigo">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</div>
