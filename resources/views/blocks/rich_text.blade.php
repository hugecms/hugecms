@component('blocks._layout', ['data' => $data])
    <div class="prose prose-gray dark:prose-invert max-w-none">
        {!! $data['content'] ?? '' !!}
    </div>
@endcomponent
