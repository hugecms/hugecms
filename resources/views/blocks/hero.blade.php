@component('blocks._layout', ['data' => $data])
    <div class="relative rounded-2xl overflow-hidden min-h-[300px] flex items-center justify-center"
         @if (! empty($data['background_image'])) style="background-image: url('{{ asset('storage/'.$data['background_image']) }}'); background-size: cover; background-position: center;" @endif>
        @if (! empty($data['background_image']))
            <div class="absolute inset-0 bg-black/40"></div>
        @endif
        <div class="relative z-10 text-center px-4 @if(($data['background_color'] ?? 'white') === 'dark') text-white @endif">
            <h2 class="text-3xl sm:text-5xl font-bold mb-4">{{ $data['title'] ?? '' }}</h2>
            @if (! empty($data['subtitle']))
                <p class="text-lg sm:text-xl mb-6 opacity-90">{{ $data['subtitle'] }}</p>
            @endif
            @if (! empty($data['cta_text']) && ! empty($data['cta_url']))
                <a href="{{ $data['cta_url'] }}"
                   class="inline-block px-6 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                    {{ $data['cta_text'] }}
                </a>
            @endif
        </div>
    </div>
@endcomponent
