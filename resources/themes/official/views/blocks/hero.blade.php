<section class="antd-card text-center py-10 px-6 space-y-3 bg-[#fafafa] dark:bg-[#1f1f1f]">
    @if (! empty($data['badge']))
        <span class="antd-tag">
            {{ $data['badge'] }}
        </span>
    @endif
    
    @if (! empty($data['title']))
        <h2 class="text-2xl sm:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">
            {{ $data['title'] }}
        </h2>
    @endif

    @if (! empty($data['subtitle']))
        <p class="text-slate-500 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
            {{ $data['subtitle'] }}
        </p>
    @endif

    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div class="pt-2">
            <a href="{{ $data['button_url'] }}" class="antd-btn-primary px-5 py-2 text-xs shadow-sm inline-block">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</section>
