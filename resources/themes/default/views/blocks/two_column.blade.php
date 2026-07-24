<div class="block-two-column">
    <div class="block-col">
        @if (! empty($data['title']))
            <h3 class="block-col-title">{{ $data['title'] }}</h3>
        @endif
        <div class="prose block-col-text">
            {!! $data['left_content'] ?? '' !!}
        </div>
    </div>
    <div class="prose block-col-text">
        {!! $data['right_content'] ?? '' !!}
    </div>
</div>
