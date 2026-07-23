@if (! empty($data['url']))
    <div class="my-6 rounded-2xl overflow-hidden shadow-md">
        <img src="{{ $data['url'] }}" alt="{{ $data['alt'] ?? '' }}" class="w-full h-auto object-cover">
        @if (! empty($data['caption']))
            <p class="text-center text-xs text-slate-400 mt-2 italic">{{ $data['caption'] }}</p>
        @endif
    </div>
@endif
