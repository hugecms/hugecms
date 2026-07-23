@component('blocks._layout', ['data' => $data])
    <div class="text-center rounded-xl p-8 @if(($data['style'] ?? 'primary') === 'primary') bg-blue-600 text-white @else bg-gray-200 dark:bg-gray-800 @endif">
        <h3 class="text-2xl font-bold mb-3">{{ $data['title'] ?? '' }}</h3>
        @if (! empty($data['description']))
            <p class="mb-6 opacity-90">{{ $data['description'] }}</p>
        @endif
        @if (! empty($data['button_text']) && ! empty($data['button_url']))
            <a href="{{ $data['button_url'] }}"
               class="inline-block px-6 py-3 rounded-lg font-medium transition
                      @if(($data['style'] ?? 'primary') === 'primary') bg-white text-blue-600 hover:bg-gray-100
                      @else bg-blue-600 text-white hover:bg-blue-700 @endif">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
@endcomponent
