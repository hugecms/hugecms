<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center py-6">
    <div class="space-y-4">
        @if (! empty($data['title']))
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $data['title'] }}</h3>
        @endif
        <div class="prose text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
            {!! $data['left_content'] ?? '' !!}
        </div>
    </div>
    <div class="prose text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
        {!! $data['right_content'] ?? '' !!}
    </div>
</div>
