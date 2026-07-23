@if (! empty($data['url']))
    <div class="my-6 rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-800">
        <img src="{{ $data['url'] }}" alt="{{ $data['alt'] ?? '' }}" class="w-full h-auto object-cover">
        @if (! empty($data['caption']))
            <p class="text-center text-xs text-slate-400 mt-2 font-mono">{{ $data['caption'] }}</p>
        @endif
    </div>
@endif
