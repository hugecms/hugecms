@php
    $hasChildren = ! empty($item['children']);
    $isActive = request()->url() === $item['url'];
@endphp

@if ($hasChildren)
    <div class="relative group" x-data="{ open: false }">
        <button type="button" 
                class="flex items-center gap-1 font-medium transition-colors hover:text-blue-600 dark:hover:text-blue-400 py-2 {{ $isActive ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-700 dark:text-slate-200' }}">
            <span>{{ $item['title'] }}</span>
            <svg class="w-3.5 h-3.5 opacity-70 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="absolute left-0 mt-1 w-56 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200/80 dark:border-slate-800 py-2 hidden group-hover:block transition-all duration-200 z-50">
            @foreach ($item['children'] as $child)
                <a href="{{ $child['url'] }}" 
                   target="{{ $child['target'] }}" 
                   class="block px-4 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    {{ $child['title'] }}
                </a>
            @endforeach
        </div>
    </div>
@else
    <a href="{{ $item['url'] }}" 
       target="{{ $item['target'] }}" 
       class="font-medium transition-colors hover:text-blue-600 dark:hover:text-blue-400 py-2 {{ $isActive ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-700 dark:text-slate-200' }}">
        {{ $item['title'] }}
    </a>
@endif
