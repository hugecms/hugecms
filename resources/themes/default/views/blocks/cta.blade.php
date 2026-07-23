<div class="p-8 rounded-3xl bg-indigo-50 dark:bg-slate-800 border border-indigo-100 dark:border-slate-700/60 text-center space-y-4 shadow-sm">
    @if (! empty($data['title']))
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $data['title'] }}</h3>
    @endif
    @if (! empty($data['description']))
        <p class="text-xs text-slate-600 dark:text-slate-400 max-w-md mx-auto">{{ $data['description'] }}</p>
    @endif
    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div>
            <a href="{{ $data['button_url'] }}" class="inline-block px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition-colors shadow-md shadow-indigo-500/20">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</div>
