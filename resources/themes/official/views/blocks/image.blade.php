@if (! empty($data['url']))
    <div class="block-image">
        <img src="{{ $data['url'] }}" alt="{{ $data['alt'] ?? '' }}">
        @if (! empty($data['caption']))
            <p class="block-image-caption">{{ $data['caption'] }}</p>
        @endif
    </div>
@endif
