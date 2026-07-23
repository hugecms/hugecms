<div class="antd-card p-6 text-center space-y-3">
    @if (! empty($data['title']))
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $data['title'] }}</h3>
    @endif
    @if (! empty($data['description']))
        <p class="text-xs text-slate-500 max-w-md mx-auto">{{ $data['description'] }}</p>
    @endif
    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div>
            <a href="{{ $data['button_url'] }}" class="antd-btn-primary px-5 py-2 text-xs shadow-sm inline-block">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</div>
