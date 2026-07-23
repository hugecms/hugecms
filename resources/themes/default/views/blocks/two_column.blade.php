@component('blocks._layout', ['data' => $data])
    <div class="grid gap-8 md:grid-cols-2 items-center {{ ($data['swap_on_mobile'] ?? false) ? 'flex-col-reverse md:flex-row' : '' }}">
        <div class="prose prose-gray dark:prose-invert max-w-none">
            {!! $data['left_content'] ?? '' !!}
        </div>
        <div>
            @if (! empty($data['right_image']))
                <img src="{{ asset('storage/'.$data['right_image']) }}"
                     alt=""
                     class="rounded-lg w-full h-auto">
            @endif
        </div>
    </div>
@endcomponent
