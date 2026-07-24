<div class="block-two-column">
    <div class="block-two-column-text">
        @if (! empty($data['title']))
            <h3 class="block-col-title">{{ $data['title'] }}</h3>
        @endif
        <div class="prose block-col-content">
            {!! $data['left_content'] ?? '' !!}
        </div>
    </div>
    <div class="prose block-col-content">
        {!! $data['right_content'] ?? '' !!}
    </div>
</div>
