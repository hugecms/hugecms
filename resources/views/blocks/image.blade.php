@component('blocks._layout', ['data' => $data])
    <div class="text-center">
        @if (! empty($data['link']))
            <a href="{{ $data['link'] }}">
        @endif
        <img src="{{ asset('storage/'.$data['image']) }}"
             alt="{{ $data['alt'] ?? '' }}"
             class="mx-auto rounded-lg max-w-full h-auto">

        @if (! empty($data['link']))
            </a>
        @endif
    </div>
@endcomponent
