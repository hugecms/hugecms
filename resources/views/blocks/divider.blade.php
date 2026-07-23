@component('blocks._layout', ['data' => $data])
    <div class="flex justify-center">
        @switch($data['style'] ?? 'line')
            @case('dots')
                <div class="w-full border-t-2 border-dotted border-gray-300 dark:border-gray-700"></div>
                @break
            @case('gradient')
                <div class="w-full h-1 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                @break
            @default
                <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
        @endswitch
    </div>
@endcomponent
