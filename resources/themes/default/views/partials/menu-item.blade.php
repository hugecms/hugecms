@php
    $hasChildren = ! empty($item['children']);
    $isActive = request()->url() === $item['url'];
@endphp

@if ($hasChildren)
    <div class="relative group" x-data="{ open: false }">
        <button type="button" 
                class="flex items-center gap-1 font-medium transition-colors hover:text-indigo-600 dark:hover:text-indigo-400 {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300' }}">
            <span>{{ $item['title'] }}</span>
            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700/60 py-2 hidden group-hover:block transition-all duration-200 z-50">
            @foreach ($item['children'] as $child)
                <a href="{{ $child['url'] }}" 
                   target="{{ $child['target'] }}" 
                   class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-slate-700/50 hover:text-indigo-600 dark:hover:text-indigo-400">
                    {{ $child['title'] }}
                </a>
            @endforeach
        </div>
    </div>
@else
    <a href="{{ $item['url'] }}" 
       target="{{ $item['target'] }}" 
       class="font-medium transition-colors hover:text-indigo-600 dark:hover:text-indigo-400 {{ $isActive ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-300' }}">
        {{ $item['title'] }}
    </a>
@endif
