<section class="py-12 px-8 rounded-2xl bg-zinc-900 text-white space-y-4 shadow-sm text-center">
    @if (! empty($data['badge']))
        <span class="inline-block px-3 py-1 rounded-full text-xs font-mono bg-zinc-800 text-amber-400">
            {{ $data['badge'] }}
        </span>
    @endif
    
    @if (! empty($data['title']))
        <h2 class="text-3xl sm:text-4xl font-serif font-black tracking-tight text-white">
            {{ $data['title'] }}
        </h2>
    @endif

    @if (! empty($data['subtitle']))
        <p class="text-zinc-400 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
            {{ $data['subtitle'] }}
        </p>
    @endif

    @if (! empty($data['button_text']) && ! empty($data['button_url']))
        <div class="pt-4">
            <a href="{{ $data['button_url'] }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white text-zinc-900 font-bold text-xs hover:bg-zinc-100 transition-colors">
                {{ $data['button_text'] }}
            </a>
        </div>
    @endif
</section>
